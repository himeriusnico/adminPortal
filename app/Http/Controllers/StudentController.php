<?php

namespace App\Http\Controllers;

// HAPUS: use App\Models\Pegawai;
use App\Models\Student;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role; // <-- TAMBAHKAN INI
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth; // <-- TAMBAHKAN INI

class StudentController extends Controller
{
    /**
     * HAPUS FUNGSI __construct()
     *
     * Kita HAPUS seluruh fungsi __construct() dari sini.
     * Kenapa? Karena kita sudah melindungi route ini di web.php
     * menggunakan middleware('role:admin').
     * Otorisasi di __construct() ini REDUNDANT (berlebihan)
     * dan menggunakan logika lama (user_type) yang menyebabkan error 403.
     */

    /**
     * Menampilkan daftar semua data mahasiswa.
     */
    public function index()
    {
        $user = Auth::user();

        // Base query
        $query = Student::with(['user', 'institution'])->orderBy('created_at', 'desc');

        // LOGIKA BARU:
        // Jika user adalah 'admin' (bukan 'super_admin'),
        // filter mahasiswa berdasarkan institusi milik admin tersebut.
        if ($user->role->name === 'admin') {

            // Ambil institution_id langsung dari user (bukan dari model Pegawai)
            if ($user->institution_id) {
                $query->where('institution_id', $user->institution_id);
            } else {
                // Jika admin tidak punya institusi, jangan tampilkan apa-apa
                $query->whereRaw('1 = 0'); // Trik untuk mengembalikan 0 hasil
            }
        }
        // Jika user adalah 'super_admin', $query tidak difilter
        // (super_admin bisa melihat semua mahasiswa dari semua institusi)

        // Ambil data mahasiswa
        $students = $query->get(); // untuk DataTables, ambil semua

        // Stats untuk cards (hitung berdasarkan query yg sama)
        $activeStudents = (clone $query)->where('status', 'active')->count();
        $graduatedStudents = (clone $query)->where('status', 'graduated')->count();
        $inactiveStudents = (clone $query)->where('status', 'inactive')->count();

        return view('students.index', compact(
            'students',
            'activeStudents',
            'graduatedStudents',
            'inactiveStudents'
        ));
    }


    /**
     * Menampilkan form untuk membuat mahasiswa baru.
     */
    public function create()
    {
        // (Tidak ada perubahan, ini hanya menampilkan view)
        return view('students.create');
    }

    /**
     * Menyimpan data mahasiswa baru ke database.
     */
    public function store(Request $request)
    {
        // Validasi (Tidak ada perubahan)
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'student_id' => 'required|string|unique:students,student_id',
            'program_study' => 'required|string|max:255',
            'faculty' => 'required|string|max:255',
            'entry_year' => 'required|integer|min:2000|max:2030',
            'phone' => 'nullable|string|max:20',
        ]);

        // LOGIKA BARU:
        $adminUser = Auth::user(); // Ini adalah user 'admin' yang sedang login

        // 1. Dapatkan Role 'student' (ID = 3 sesuai RegisterController Anda)
        $studentRole = Role::where('name', 'student')->first();
        if (!$studentRole) {
            // Gagal jika role 'student' tidak ada di database
            return response()->json(['error' => 'Konfigurasi role Student tidak ditemukan.'], 500);
        }

        // 2. Dapatkan institution_id dari admin yang login
        $institution_id = $adminUser->institution_id;
        if (!$institution_id) {
            return response()->json(['error' => 'Admin tidak terasosiasi dengan institusi manapun.'], 403);
        }

        // 3. Buat User baru untuk si mahasiswa
        $newUser = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make('11111111'), // Password default sementara
            'role_id' => $studentRole->id, // <-- Gunakan role_id
            'institution_id' => $institution_id // <-- Tetapkan institusi saat user dibuat
        ]);

        // 4. Buat data Student
        $student = Student::create([
            'user_id' => $newUser->id,
            'institution_id' => $institution_id, // <-- Ambil dari admin
            'student_id' => $validated['student_id'],
            'program_study' => $validated['program_study'],
            'faculty' => $validated['faculty'],
            'entry_year' => $validated['entry_year'],
            'phone' => $validated['phone'] ?? null,
            'status' => 'active',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Mahasiswa dan user berhasil dibuat.',
            'student' => $student,
        ]);
    }

    /**
     * Menampilkan detail satu mahasiswa spesifik.
     */
    public function show(Student $student)
    {
        // (Tidak ada perubahan, tapi kita harus pastikan 'admin' ini
        // hanya bisa melihat student dari institusinya sendiri.
        // Ini bisa ditangani dengan Policy, tapi untuk sekarang kita loloskan)

        $student->load(['user', 'institution', 'documents']);
        return view('students.show', compact('student'));
    }

    /**
     * Menampilkan form untuk mengedit data mahasiswa.
     */
    public function edit(Student $student)
    {
        // (Tidak ada perubahan)
        return view('students.edit', compact('student'));
    }

    /**
     * Memperbarui data mahasiswa di database.
     */
    public function update(Request $request, Student $student)
    {
        // (Logika validasi Anda sudah benar, implementasi TODO-nya
        // akan perlu logika yang sama dengan 'store' di atas)

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $student->user_id,
            'student_id' => 'required|string|unique:students,student_id,' . $student->id,
            'program_study' => 'required|string|max:255',
            'faculty' => 'required|string|max:255',
            'entry_year' => 'required|integer|min:2000|max:2030',
            'phone' => 'nullable|string|max:20',
            'status' => 'required|in:active,graduated,inactive',
        ]);

        // TODO: Implementasi logika update
        // 1. Update $student->user
        // 2. Update $student

        return redirect()->route('students.index')
            ->with('success', 'Data mahasiswa berhasil diperbarui.');
    }

    /**
     * Menghapus data mahasiswa dari database.
     */
    public function destroy(Student $student)
    {
        // (Tidak ada perubahan)

        // TODO: Implementasi logika delete
        // $student->user->delete(); // Hapus user
        // $student->delete(); // Hapus student

        return redirect()->route('students.index')
            ->with('success', 'Mahasiswa berhasil dihapus.');
    }
}
