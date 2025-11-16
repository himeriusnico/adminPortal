<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\Student;
use App\Models\DocumentType;
use App\Models\EncryptedKey;
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
    Log::info('STORE: Request diterima. Input:', $request->except(['file', 'passphrase']));
    Log::info('STORE: Passphrase Length:', ['length' => strlen($request->input('passphrase'))]);

    // ✅ VALIDASI
    $validated = $request->validate([
      'student_id' => 'required|integer|exists:students,id',
      'document_type_id' => 'required|integer|exists:document_types,id',
      'file' => 'required|file|mimes:pdf|max:2048',
      'passphrase' => 'required|string|min:8',
    ]);

    Log::info('STORE: Validasi BERHASIL.');

    try {
      $admin = Auth::user();
      Log::info('STORE: Admin retrieved', [
        'admin_id' => $admin?->id,
        'admin_institution_id' => $admin?->institution_id,
      ]);

      if (!$admin) {
        return response()->json([
          'success' => false,
          'message' => 'Gagal mengambil data user'
        ], 401);
      }

      $student = Student::find($request->student_id);
      Log::info('STORE: Student retrieved', [
        'student_id' => $student?->id,
        'student_institution_id' => $student?->institution_id,
      ]);

      if (!$student) {
        return response()->json([
          'success' => false,
          'message' => 'Mahasiswa tidak ditemukan'
        ], 404);
      }

      if ($student->institution_id !== $admin->institution_id) {
        Log::warning('STORE: Institution mismatch', [
          'admin_inst' => $admin->institution_id,
          'student_inst' => $student->institution_id,
        ]);
        return response()->json([
          'success' => false,
          'message' => 'Akses ditolak. Anda hanya dapat mengunggah untuk mahasiswa di institusi Anda.'
        ], 403);
      }

      Log::info('STORE: Institution check passed');

      $file = $request->file('file');
      Log::info('STORE: File retrieved', [
        'filename' => $file?->getClientOriginalName(),
        'size' => $file?->getSize(),
      ]);

      if (!$file) {
        return response()->json([
          'success' => false,
          'message' => 'File tidak ditemukan'
        ], 400);
      }

      Log::info('STORE: Before accessing institution relation', [
        'admin_id' => $admin->id,
        'institution_id' => $admin->institution_id,
      ]);

      $institution = $admin->institution;

      Log::info('STORE: After accessing institution relation', [
        'institution' => $institution?->toArray(),
      ]);

      if (!$institution) {
        Log::error('STORE: Institution adalah NULL setelah relation access');
        return response()->json([
          'success' => false,
          'message' => 'Institusi admin tidak ditemukan'
        ], 404);
      }

      Log::info('STORE: Before accessing encrypted key relation', [
        'institution_id' => $institution->id,
        'institution_name' => $institution->name,
      ]);

      $encryptedKey = $institution->encryptedKey;

      Log::info('STORE: After accessing encrypted key relation', [
        'found' => $encryptedKey ? true : false,
        'key_id' => $encryptedKey?->id,
      ]);

      if (!$encryptedKey) {
        Log::error('STORE: No encrypted key found for institution', [
          'institution_id' => $institution->id,
          'institution_name' => $institution->name,
        ]);
        return response()->json([
          'success' => false,
          'message' => 'Konfigurasi Error: Institution ' . $institution->name . ' belum memiliki encrypted key. Hubungi administrator.'
        ], 409);
      }

      if (empty($encryptedKey->encrypted_private_key)) {
        Log::error('STORE: encrypted_private_key is empty', [
          'key_id' => $encryptedKey->id
        ]);
        return response()->json([
          'success' => false,
          'message' => 'Error: Encrypted key data kosong'
        ], 409);
      }

      $passphrase = $request->input('passphrase');


      //ini buat ulang lagi kunci aes nya
      $derivedKey = hash_pbkdf2(
        'sha256',
        $passphrase,
        hex2bin($encryptedKey->salt),
        100000,
        32, //nilai dari kunci e
        true
      );

      //dekripsi aes
      $privateKeyPem = openssl_decrypt(
        $encryptedKey->encrypted_private_key,
        'aes-256-cbc',
        $derivedKey,
        0,
        hex2bin($encryptedKey->iv)
      );

      if (!$privateKeyPem) {
        Log::error('STORE: Gagal mendekripsi kunci privat. Passphrase salah?');
        return response()->json([
          'success' => false,
          'message' => 'Gagal mendekripsi kunci privat: Passphrase mungkin salah.'
        ], 400);
      }

      Log::info('STORE: Kunci privat berhasil didekripsi.');

      try {
        $hashValueBinary = hash_file('sha256', $file->getRealPath(), true);

        /** @var \phpseclib3\Crypt\EC\PrivateKey $privateKey */
        $privateKey = EC::load($privateKeyPem);

        $signature = $privateKey->sign($hashValueBinary);
        $signatureBase64 = base64_encode($signature);

        Log::info('STORE: Digital signature berhasil dibuat');
      } catch (NoKeyLoadedException $e) {
        Log::error('STORE: Gagal memuat private key: ' . $e->getMessage());
        return response()->json([
          'success' => false,
          'message' => 'Gagal memuat private key: Kunci rusak atau tidak valid.'
        ], 400);
      } catch (\Exception $e) {
        Log::error('STORE: Gagal membuat digital signature: ' . $e->getMessage());
        return response()->json([
          'success' => false,
          'message' => 'Gagal membuat digital signature: ' . $e->getMessage()
        ], 400);
      }

      try {
        $filename = time() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();

        $path = $file->storeAs('documents/' . $student->id, $filename, 'private');
        $hashDocument = hash_file('sha256', $file->getRealPath());

        Log::info('STORE: File tersimpan', [
          'path' => $path,
          'hash' => $hashDocument,
        ]);
      } catch (\Exception $e) {
        Log::error('STORE: ERROR at file storage', [
          'message' => $e->getMessage(),
        ]);
        return response()->json([
          'success' => false,
          'message' => 'Gagal menyimpan file: ' . $e->getMessage()
        ], 500);
      }

      try {
        $document = Document::create([
          'student_id' => $student->id,
          'institution_id' => $admin->institution_id,
          'filename' => $filename,
          'document_type_id' => $request->document_type_id,
          'file_path' => $path,
          'hash' => $hashDocument,
          'signature' => $signatureBase64,
        ]);

        Log::info('STORE: Document record created', [
          'document_id' => $document->id,
        ]);
      } catch (\Exception $e) {
        Log::error('STORE: ERROR at database save', [
          'message' => $e->getMessage(),
        ]);
        return response()->json([
          'success' => false,
          'message' => 'Gagal menyimpan data dokumen: ' . $e->getMessage()
        ], 500);
      }

      // // ========== ZONE 11: Submit to Blockchain ==========
      // // Temporarily disable submission to blockchain — comment out the network call
      // /*
      // try {
      //   $documentTypeName = DocumentType::find($request->document_type_id)->name;
      //   $institutionPublicKey = $institution->public_key;

      //   $payload = [
      //     'documentId' => $admin->institution_id . '-' . str_pad($document->id, 6, '0', STR_PAD_LEFT),
      //     'studentId' => $student->id,
      //     'institutionId' => $admin->institution_id,
      //     'filename' => $filename,
      //     'documentType' => $documentTypeName,
      //     'hash' => $hashDocument,
      //     'signature' => $signatureBase64,
      //     'institutionPublicKey' => $institutionPublicKey,
      //     'createdAt' => now()->toIso8601String(),
      //     'status' => 'issued'
      //   ];

      //   Log::info('STORE: Submitting to blockchain', [
      //     'payload' => $payload,
      //   ]);

      //   $response = Http::post(config('blockchain.api_url') . '/submitSignature', $payload);

      //   if (!$response->successful()) {
      //     Log::warning('STORE: Blockchain submission failed: ' . $response->body());
      //   } else {
      //     Log::info('STORE: Blockchain submission successful');
      //   }
      // } catch (\Exception $e) {
      //   Log::error('STORE: Blockchain submission error: ' . $e->getMessage());
      //   // Tidak return error, tetap success karena dokumen sudah tersimpan
      // }
      // */

      // Log::info('STORE: Blockchain submission skipped (temporarily disabled)');

      // ========== ZONE 12: Success ==========
      Log::info('STORE: Proses upload dokumen BERHASIL selesai');

      return response()->json([
        'success' => true,
        'message' => 'Dokumen berhasil diunggah.',
        'document_id' => $document->id
      ], 200);
    } catch (\Illuminate\Validation\ValidationException $e) {
      Log::error('STORE: Validation error', ['errors' => $e->errors()]);
      return response()->json([
        'success' => false,
        'message' => 'Validasi gagal',
        'errors' => $e->errors()
      ], 422);
    } catch (\Exception $e) {
      Log::error('STORE: Unexpected error', [
        'message' => $e->getMessage(),
        'file' => $e->getFile(),
        'line' => $e->getLine(),
      ]);
      return response()->json([
        'success' => false,
        'message' => 'Terjadi kesalahan: ' . $e->getMessage()
      ], 500);
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
      // 'documentId' => $admin->institution_id . '-' . str_pad($document->id, 6, '0', STR_PAD_LEFT),
      'documentId' => (string) $document->id,
      'studentId' => $student->id,
      'institutionId' => $admin->institution_id,
      'filename' => $document->filename,
      'documentType' => $documentType->name,
      'hash' => $document->hash,
      'signature' => $document->signature,
      'institutionPublicKey' => $institution->public_key,
      'createdAt' => $document->created_at->toIso8601String(),
      'status' => 'issued',
    ];

    $url = config('blockchain.api_url') . '/api/documents';

    try {
      $response = Http::timeout(10)->post($url, $payload);

      if ($response->successful()) {

        $result = $response->json();

        // simpan hash transaksi
        $document->tx_id = $result['txId'];
        $document->save();

        return response()->json([
          'success' => true,
          'message' => 'Berhasil diverifikasi ke blockchain',
          'tx_id' => $result['txId']
        ]);
      }

      Log::warning('Blockchain rejected the request', [
        'url' => $url,
        'payload' => $payload,
        'response_body' => $response->body(),
      ]);

      return response()->json([
        'success' => false,
        'message' => 'Gagal mengirim ke blockchain: ' . $response->body(),
      ], 500);
    } catch (\Throwable $e) {
      Log::error('Blockchain submission error', [
        'url' => $url,
        'payload' => $payload,
        'exception' => $e->getMessage(),
      ]);

      return response()->json([
        'success' => false,
        'message' => 'Terjadi kesalahan saat mengirim ke blockchain: ' . $e->getMessage(),
      ], 500);
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
}
