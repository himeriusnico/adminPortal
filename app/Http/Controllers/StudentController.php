<?php

namespace App\Http\Controllers;

// HAPUS: use App\Models\Pegawai;

use App\Models\Faculty;
use App\Models\ProgramStudy;
use App\Models\Student;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role; 
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth; 

class StudentController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $query = Student::with(['user', 'institution'])->orderBy('created_at', 'desc');

        $institution_id = $user->institution_id;

        if ($user->role->name === 'admin') {
            if ($institution_id) {
                $query->where('institution_id', $institution_id);
            } else {
                // Jika admin tidak punya institusi, jangan tampilkan apa- apa
                $query->whereRaw('1 = 0'); // Trik untuk mengembalikan 0 hasil
            }
        }
        // Jika user adalah 'super_admin', $query tidak difilter
        // (super_admin bisa melihat semua mahasiswa dari semua institusi)

        // Ambil data mahasiswa
        $students = $query->get(); // untuk DataTables, ambil semua

        $faculties = Faculty::where('institution_id', $institution_id)
            ->orderBy('name')
            ->get();

        $facultyIds = $faculties->pluck('id');
        $programStudies = ProgramStudy::whereIn('faculty_id', $facultyIds)
            ->orderBy('name')
            ->get();

        // Stats untuk cards (hitung berdasarkan query yg sama)
        $activeStudents = (clone $query)->where('status', 'active')->count();
        $graduatedStudents = (clone $query)->where('status', 'graduated')->count();
        $inactiveStudents = (clone $query)->where('status', 'inactive')->count();

        return view('students.index', compact(
            'students',
            'activeStudents',
            'graduatedStudents',
            'inactiveStudents',
            'faculties',
            'programStudies'
        ));
    }

    public function create()
    {
        return view('students.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'student_id' => 'required|string|unique:students,student_id',
            'faculty_id' => 'required|exists:faculties,id',
            'program_study_id' => 'required|exists:program_studies,id',
            'entry_year' => 'required|integer|min:2000|max:2030',
            'phone' => 'nullable|string|max:20',
            'status' => 'required|in:active,graduated,inactive',
            'graduation_date' => 'nullable|date|after_or_equal:entry_year',
        ]);

        $adminUser = Auth::user(); // Ini adalah user 'admin' yang sedang login
        $institution_id = $adminUser->institution_id;

        try {
            $studentRole = Role::where('name', 'student')->first();
            $newUser = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make('11111111'), // Password default sementara
                'role_id' => $studentRole->id, 
                'institution_id' => $institution_id 
            ]);

            $student = Student::create([
                'user_id' => $newUser->id,
                'institution_id' => $institution_id,
                'student_id' => $validated['student_id'],
                'faculty_id' => $request->faculty_id,
                'program_study_id' => $request->program_study_id,
                'entry_year' => $validated['entry_year'],
                'phone' => $validated['phone'] ?? null,
                'status' => $validated['status'],
                'graduation_date' => $validated['graduation_date'] ?? null,
            ]);

            // $faculty = Faculty::find($validated['faculty_id']);
            // $programStudy = ProgramStudy::find($validated['program_study_id']);

            return response()->json([
                'success' => true,
                'message' => 'Mahasiswa dan user berhasil dibuat.',
                'student' => $student,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan mahasiswa: ' . $e->getMessage()
            ], 500);
        }
        // 1. Dapatkan Role 'student' (ID = 3 sesuai RegisterController Anda)
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
