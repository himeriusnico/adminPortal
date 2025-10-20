<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function __construct()
    {
        // Hanya admin dan pegawai yang bisa mengakses students
        $this->middleware(function ($request, $next) {
            if (!in_array(auth()->user()->user_type, ['admin', 'pegawai'])) {
                abort(403, 'Unauthorized access.');
            }
            return $next($request);
        });
    }

    /**
     * Menampilkan daftar semua data mahasiswa.
     */
    public function index()
    {
        $user = auth()->user();

        // Base query
        $query = Student::with(['user', 'institution'])->orderBy('created_at', 'desc');

        // Jika user adalah pegawai, filter berdasarkan institution_id
        if ($user->user_type === 'pegawai') {
            $pegawai = \App\Models\Pegawai::where('users_id', $user->id)->first();
            if ($pegawai) {
                $query->where('institution_id', $pegawai->institution_id);
            }
        }

        // Ambil data mahasiswa
        $students = $query->get(); // untuk DataTables, ambil semua (tanpa paginate)

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
        return view('students.create');
    }

    /**
     * Menyimpan data mahasiswa baru ke database.
     */
    public function store(Request $request)
    {
        // Validasi
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'student_id' => 'required|string|unique:students,student_id',
            'program_study' => 'required|string|max:255',
            'faculty' => 'required|string|max:255',
            'entry_year' => 'required|integer|min:2000|max:2030',
            'phone' => 'nullable|string|max:20',
        ]);

        // TODO: Implementasi logika penyimpanan
        // 1. Create user
        // 2. Create student record
        // 3. Attach to institution based on logged-in user

        return redirect()->route('students.index')
            ->with('success', 'Mahasiswa berhasil ditambahkan.');
    }

    /**
     * Menampilkan detail satu mahasiswa spesifik.
     */
    public function show(Student $student)
    {
        $student->load(['user', 'institution', 'documents']);
        return view('students.show', compact('student'));
    }

    /**
     * Menampilkan form untuk mengedit data mahasiswa.
     */
    public function edit(Student $student)
    {
        return view('students.edit', compact('student'));
    }

    /**
     * Memperbarui data mahasiswa di database.
     */
    public function update(Request $request, Student $student)
    {
        // Validasi
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

        return redirect()->route('students.index')
            ->with('success', 'Data mahasiswa berhasil diperbarui.');
    }

    /**
     * Menghapus data mahasiswa dari database.
     */
    public function destroy(Student $student)
    {
        // TODO: Implementasi logika delete
        // 1. Delete student record
        // 2. Delete user record (optional, depending on requirements)

        return redirect()->route('students.index')
            ->with('success', 'Mahasiswa berhasil dihapus.');
    }
}
