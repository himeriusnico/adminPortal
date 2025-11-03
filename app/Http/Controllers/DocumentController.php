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
      ->with(['student.user', 'institution']) // Ambil relasi
      ->latest() // Urutkan dari yang terbaru
      ->get();

    return view('documents.index', compact('students', 'documents'));
  }

  /**
   * Menyimpan dokumen yang baru di-upload.
   */
  public function store(Request $request)
  {
    // 1. Validasi
    $request->validate([
      'student_id' => 'required|integer|exists:students,id',
      'document_type' => 'required|string|in:dokumen_ijazah,transkrip,skpi',
      'file' => 'required|file|mimes:pdf|max:2048', // Maks 2MB, hanya PDF
    ]);

    $admin = Auth::user();
    $student = Student::find($request->student_id);

    // 2. Keamanan: Pastikan admin tidak mengunggah untuk mahasiswa di institusi lain
    if ($student->institution_id !== $admin->institution_id) {
      abort(403, 'Akses ditolak. Anda hanya dapat mengunggah untuk mahasiswa di institusi Anda.');
    }

    // 3. Simpan File
    $file = $request->file('file');
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
      'document_type' => $request->document_type,
      'file_path' => $path,
      'hash' => 'PENDING_HASH_CALCULATION', // TODO: Kalkulasi hash setelah upload
      'signature' => 'PENDING_SIGNATURE', // TODO: Buat signature
      // 'document_type_id' => ... (Jika Anda punya logika untuk ini)
    ]);

    return back()->with('success', 'Dokumen berhasil diunggah.');
  }
}
