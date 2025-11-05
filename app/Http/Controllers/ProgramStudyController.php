<?php

namespace App\Http\Controllers;

use App\Models\ProgramStudy;
use App\Models\Faculty;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProgramStudyController extends Controller
{
  /**
   * Store a new program study
   */
  public function store(Request $request)
  {
    $request->validate([
      'name' => 'required|string|max:255',
      'faculty_id' => 'required|exists:faculties,id',
    ]);

    $user = Auth::user();

    // Check if faculty belongs to user's institution
    $faculty = Faculty::findOrFail($request->faculty_id);
    if ($faculty->institution_id !== $user->institution_id) {
      return redirect()
        ->route('institutions.settings')
        ->with('error', 'Fakultas tidak ditemukan.');
    }

    try {
      ProgramStudy::create([
        'name' => $request->name,
        'faculty_id' => $request->faculty_id,
        'university_id' => $user->institution_id,
      ]);

      return redirect()
        ->route('institutions.settings')
        ->with('success', 'Program studi berhasil ditambahkan!');
    } catch (\Exception $e) {
      return redirect()
        ->route('institutions.settings')
        ->with('error', 'Gagal menambahkan program studi: ' . $e->getMessage());
    }
  }

  /**
   * Delete a program study
   */
  public function destroy(ProgramStudy $programStudy)
  {
    $user = Auth::user();

    // Security check
    if ($programStudy->university_id !== $user->institution_id) {
      return redirect()
        ->route('institutions.settings')
        ->with('error', 'Anda tidak memiliki akses untuk menghapus program studi ini.');
    }

    try {
      $programStudy->delete();

      return redirect()
        ->route('institutions.settings')
        ->with('success', 'Program studi berhasil dihapus!');
    } catch (\Exception $e) {
      return redirect()
        ->route('institutions.settings')
        ->with('error', 'Gagal menghapus program studi: ' . $e->getMessage());
    }
  }
}
