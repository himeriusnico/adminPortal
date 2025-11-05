<?php

namespace App\Http\Controllers; // Pastikan namespace controller juga benar

use App\Models\Document;
use App\Models\Student; // <-- Ubah titik jadi backslash
use App\Models\DocumentType; // <-- Ubah titik jadi backslash
use Illuminate\Http\Request; // <-- Ubah titik jadi backslash
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DocumentController extends Controller
{
  /**
   * Menampilkan halaman upload dan daftar dokumen.
   */
  public function index()
  {
    $admin = Auth::user();

    // 1. Ambil mahasiswa HANYA dari institusi admin
    $students = Student::where('institution_id', $admin->institution_id)
      ->with('user') // Ambil relasi user untuk tampilkan nama
      ->get()
      ->sortBy('user.name'); // Urutkan berdasarkan nama dari relasi user

    // 2. Ambil dokumen HANYA dari institusi admin
    $documents = Document::where('institution_id', $admin->institution_id)
      ->with(['student.user', 'institution', 'documentType']) // Ambil relasi
      ->latest() // Urutkan dari yang terbaru
      ->get();

    $documentTypes = DocumentType::all();

    return view('documents.index', compact('students', 'documents', 'documentTypes'));
  }

  /**
   * Menyimpan dokumen yang baru di-upload.
   */
  public function store(Request $request)
  {
    // 1. Validasi
    $request->validate([
      'student_id' => 'required|integer|exists:students,id',
      'document_type_id' => 'required|integer|exists:document_types,id',
      'file' => 'required|file|mimes:pdf|max:2048', // Maks 2MB, hanya PDF
    ]);

    // dd($request->all());

    $admin = Auth::user();
    $student = Student::find($request->student_id);

    // 2. Keamanan: Pastikan admin tidak mengunggah untuk mahasiswa di institusi lain
    if ($student->institution_id !== $admin->institution_id) {
      abort(403, 'Akses ditolak. Anda hanya dapat mengunggah untuk mahasiswa di institusi Anda.');
    }

    // 3. Simpan File
    $file = $request->file('file');

    $hashValue = hash_file('sha256', $file->getRealPath());

    // Buat nama file unik: [timestamp]_[nama_file_asli].pdf
    $filename = time() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();

    // Simpan di storage/app/private/documents/[student_id]/[filename]
    // 'private' adalah nama disk. Anda bisa ganti 'local' jika belum di-setup
    $path = $file->storeAs('documents/' . $student->id, $filename, 'private');

    // 4. Buat record di Database
    // (Kita stub 'hash' dan 'signature' untuk saat ini)
    Document::create([
      'student_id' => $student->id,
      'institution_id' => $admin->institution_id,
      'filename' => $filename,
      'document_type_id' => $request->document_type_id,
      // 'document_type' => $request->document_type,
      'file_path' => $path,
      'hash' => $hashValue,
      'signature' => 'PENDING_SIGNATURE', // TODO: Buat signature
    ]);

    return back()->with('success', 'Dokumen berhasil diunggah.');
  }

  public function viewDocument(Document $document)
  {
    $admin = Auth::user();

    if ($document->institution_id !== $admin->institution_id) {
      abort(403, 'Akses ditolak. Anda hanya dapat melihat dokumen dari institusi Anda.');
    }
    try {
      /** @var \Illuminate\Filesystem\FilesystemAdapter $storage */
      // Use PHPDoc hinting biar bisa kasih tau interphelense real class type bukan hanya interface
      $storage = Storage::disk('private');
      return $storage->response($document->file_path, null, [
        'Content-Type' => 'application/pdf',
        'Content-Disposition' => 'inline; filename="' . $document->filename . '"'
      ]);
    } catch (\Exception $e) {
      return back()->with('error', 'Gagal membuka dokumen: ' . $e->getMessage());
    }
  }
}
