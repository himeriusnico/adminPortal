<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\Student;
use App\Models\DocumentType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use phpseclib3\Exception\NoKeyLoadedException;
use phpseclib3\Crypt\EC;

class DocumentController extends Controller
{
    public function index()
    {
        $admin = Auth::user();

        $students = Student::where('institution_id', $admin->institution_id)
            ->with('user')
            ->get()
            ->sortBy('user.name');

        $documents = Document::where('institution_id', $admin->institution_id)
            ->with(['student.user', 'institution', 'documentType'])
            ->latest()
            ->get();

        $documentTypes = DocumentType::all();

        return view('documents.index', compact('students', 'documents', 'documentTypes', 'admin'));
    }

    public function store(Request $request)
    {
        // 1. Validasi Input
        $request->validate([
            'student_id'       => 'required|integer|exists:students,id',
            'document_type_id' => 'required|integer|exists:document_types,id',
            'file'             => 'required|file|mimes:pdf|max:2048',
            'passphrase'       => 'required|string|min:8',
        ], [
            'file.required' => 'Silakan pilih file PDF untuk diunggah.',
            'file.max'      => 'Ukuran file maksimal 2MB.',
            'file.file'     => 'File tidak valid. Silakan unggah file PDF.',
            'file.mimes'    => 'File harus dalam bentuk PDF.'
        ]);

        $startOffChain = microtime(true);

        try {
            $admin = Auth::user();
            if (!$admin) {
                return response()->json(['success' => false, 'message' => 'Gagal mengambil data user'], 401);
            }

            $student = Student::find($request->student_id);
            if (!$student) {
                return response()->json(['success' => false, 'message' => 'Mahasiswa tidak ditemukan'], 404);
            }

            // Guard: Cek Institusi
            if ($student->institution_id !== $admin->institution_id) {
                Log::warning('STORE: Institution mismatch', [
                    'admin_inst'   => $admin->institution_id,
                    'student_inst' => $student->institution_id,
                ]);
                return response()->json([
                    'success' => false, 
                    'message' => 'Akses ditolak. Anda hanya dapat mengunggah untuk mahasiswa di institusi Anda.'
                ], 403);
            }

            $file = $request->file('file');
            if (!$file) {
                return response()->json(['success' => false, 'message' => 'File tidak ditemukan'], 400);
            }

            $institution = $admin->institution;
            if (!$institution) {
                Log::error('STORE: Institution NULL');
                return response()->json(['success' => false, 'message' => 'Institusi admin tidak ditemukan'], 404);
            }

            $encryptedKey = $institution->encryptedKey;
            if (!$encryptedKey || empty($encryptedKey->encrypted_private_key)) {
                Log::error('STORE: Missing or empty encrypted key');
                return response()->json([
                    'success' => false, 
                    'message' => 'Konfigurasi Error: Institution belum memiliki data kunci privat.'
                ], 409);
            }

            // BUSINESS RULE:
// Satu student hanya boleh memiliki satu dokumen aktif per document_type
            $existingDocument = Document::where('student_id', $student->id)
                ->where('document_type_id', $request->document_type_id)
                ->whereNull('deleted_at')
                ->first();

            if ($existingDocument && !$request->boolean('force_replace')) {
                return response()->json([
                    'success' => false,
                    'code'    => 'DOCUMENT_ALREADY_EXISTS',
                    'message' => 'Dokumen untuk jenis ini sudah ada. Apakah ingin mengganti?',
                    'document_id' => $existingDocument->id
                ], 409);
            }

            // Jika user menyetujui replace
            if ($existingDocument && $request->boolean('force_replace')) {
                $existingDocument->update([
                    'deleted_at' => now()
                ]);
            }

            // 2. Dekripsi Kunci Privat
            $passphrase = $request->input('passphrase');
            $derivedKey = hash_pbkdf2(
                'sha256',
                $passphrase,
                hex2bin($encryptedKey->salt),
                100000,
                32,
                true
            );

            $privateKeyPem = openssl_decrypt(
                $encryptedKey->encrypted_private_key,
                'aes-256-cbc',
                $derivedKey,
                0,
                hex2bin($encryptedKey->iv)
            );

            if (!$privateKeyPem) {
                Log::error('STORE: Dekripsi gagal. Passphrase salah.');
                return response()->json([
                    'success' => false, 
                    'message' => 'Gagal mendekripsi kunci privat: Passphrase mungkin salah.'
                ], 400);
            }

            // 3. Digital Signature (Penandatanganan)
            try {
                $hashValueBinary = hash_file('sha256', $file->getRealPath(), true);
                
                /** @var \phpseclib3\Crypt\EC\PrivateKey $privateKey */
                $privateKey = EC::load($privateKeyPem);
                $signature = $privateKey->sign($hashValueBinary);
                $signatureBase64 = base64_encode($signature);
            } catch (NoKeyLoadedException $e) {
                return response()->json(['success' => false, 'message' => 'Gagal memuat private key: Kunci rusak.'], 400);
            } catch (\Exception $e) {
                return response()->json(['success' => false, 'message' => 'Signature error: ' . $e->getMessage()], 400);
            }

            // 4. Pengelolaan File & Cek Duplikasi
            try {
                $documentType = DocumentType::find($request->document_type_id);
                $documentTypeName = $documentType?->name ?? 'dokumen';
                $studentName = $student->user?->name ?? 'user';

                $baseName = Str::slug($documentTypeName, '_') . '_' . Str::slug($studentName, '_');
                $filename = $baseName . '.' . $file->getClientOriginalExtension();

                $hashDocument = hash_file('sha256', $file->getRealPath());

                // Cek hash duplikat
                if (Document::where('hash', $hashDocument)->whereNull('deleted_at')->exists()) {
                    return response()->json([
                        'success' => false, 
                        'message' => 'File yang diunggah tidak boleh sama dengan file terunggah sebelumnya.'
                    ], 409);
                }

                $path = $file->storeAs('documents/' . $student->id, $filename, 'private');
            } catch (\Exception $e) {
                return response()->json(['success' => false, 'message' => 'Gagal menyimpan file: ' . $e->getMessage()], 500);
            }

            // 5. Simpan ke Database
            try {
                $document = Document::create([
                    'student_id'       => $student->id,
                    'institution_id'   => $admin->institution_id,
                    'filename'         => $filename,
                    'document_type_id' => $request->document_type_id,
                    'file_path'        => $path,
                    'hash'             => $hashDocument,
                    'signature'        => $signatureBase64,
                ]);

                $durationOffChain = microtime(true) - $startOffChain;
                Log::info("PERFORMANCE_TEST [Off-Chain]: Dokumen ID {$document->id} tersimpan.");

                return response()->json([
                    'success'    => true,
                    'message'    => 'Dokumen berhasil diunggah.',
                    'performance' => ['off_chain_duration_seconds' => $durationOffChain],
                    'document_id' => $document->id,
                    'hash'        => $hashDocument,
                    'signature'   => $signatureBase64,
                ], 200);
            } catch (\Exception $e) {
                return response()->json(['success' => false, 'message' => 'Database save error: ' . $e->getMessage()], 500);
            }

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['success' => false, 'message' => 'Validasi gagal', 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            Log::error('STORE: Unexpected error', ['message' => $e->getMessage()]);
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }

    public function viewDocument(Document $document)
    {
        $admin = Auth::user();

        if ($document->institution_id !== $admin->institution_id) {
            abort(403, 'Akses ditolak. Anda hanya dapat melihat dokumen dari institusi Anda.');
        }

        try {
            $storage = Storage::disk('private');
            return $storage->response($document->file_path, null, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="' . $document->filename . '"'
            ]);
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal membuka dokumen: ' . $e->getMessage());
        }
    }

    public function sendToBlockchain(Document $document)
    {
        $admin = Auth::user();
        $institution = $admin->institution;
        $student = $document->student;
        $documentType = $document->documentType;

        $payload = [
            'documentId'           => (string) $document->id,
            'studentId'            => (string) $student->id,
            'institutionId'        => (string) $admin->institution_id,
            'filename'             => $document->filename,
            'documentType'         => $documentType->name,
            'hash'                 => $document->hash,
            'signature'            => $document->signature,
            'institutionPublicKey' => $institution->public_key,
            'createdAt'            => $document->created_at->toIso8601String(),
            'status'               => 'issued',
        ];

        $url = config('blockchain.api_url') . '/api/documents';
        $startOnChain = microtime(true);

        try {
            $response = Http::timeout(10)->post($url, $payload);

            if ($response->successful()) {
                $result = $response->json();
                $document->tx_id = $result['txId'];
                $document->save();

                $durationOnChain = microtime(true) - $startOnChain;
                Log::info("PERFORMANCE_TEST [On-Chain]: Dokumen ID {$document->id} tercatat.");

                return response()->json([
                    'success' => true,
                    'message' => 'Berhasil diverifikasi ke blockchain',
                    'tx_id'   => $result['txId'],
                    'performance' => ['on_chain_seconds' => round($durationOnChain, 6)]
                ]);
            }

            return response()->json(['success' => false, 'message' => 'Gagal mengirim ke blockchain'], 500);
        } catch (\Throwable $e) {
            Log::error('Blockchain submission error', ['exception' => $e->getMessage()]);
            return response()->json(['success' => false, 'message' => 'Kesalahan blockchain: ' . $e->getMessage()], 500);
        }
    }

    public function getBlockchainData($id)
    {
        $document = Document::findOrFail($id);
        $response = Http::get(config('blockchain.api_url') . "/api/documents/" . $document->id . "/history");

        if ($response->failed()) {
            return response()->json(['error' => 'Gagal mengambil data blockchain'], 500);
        }

        return $response->json();
    }

    public function destroy(Document $document)
    {
        $admin = Auth::user();

        if ($document->institution_id !== $admin->institution_id) {
            return back()->with('error', 'Akses ditolak.');
        }

        if (!is_null($document->tx_id)) {
            return back()->with('error', 'Dokumen terverifikasi blockchain tidak dapat dihapus.');
        }

        try {
            if ($document->file_path) {
                Storage::disk('private')->delete($document->file_path);
            }

            $document->delete();
            return back()->with('success', 'Dokumen berhasil dihapus.');
        } catch (\Throwable $e) {
            return back()->with('error', 'Gagal menghapus dokumen: ' . $e->getMessage());
        }
    }
}