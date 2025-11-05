<?php

namespace App\Http\Controllers;

use App\Models\Faculty;
use App\Models\ProgramStudy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FacultyController extends Controller
{
    /**
     * Store a new faculty
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $user = Auth::user();

        try {
            Faculty::create([
                'name' => $request->name,
                'institution_id' => $user->institution_id,
            ]);

            return redirect()
                ->route('institutions.settings')
                ->with('success', 'Fakultas berhasil ditambahkan!');
        } catch (\Exception $e) {
            return redirect()
                ->route('institutions.settings')
                ->with('error', 'Gagal menambahkan fakultas: ' . $e->getMessage());
        }
    }

    /**
     * Delete a faculty
     */
    public function destroy(Faculty $faculty)
    {
        $user = Auth::user();

        // Security check
        if ($faculty->institution_id !== $user->institution_id) {
            return redirect()
                ->route('institutions.settings')
                ->with('error', 'Anda tidak memiliki akses untuk menghapus fakultas ini.');
        }

        try {
            $faculty->delete();

            return redirect()
                ->route('institutions.settings')
                ->with('success', 'Fakultas berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()
                ->route('institutions.settings')
                ->with('error', 'Gagal menghapus fakultas: ' . $e->getMessage());
        }
    }

    /**
     * Get program studies by faculty (AJAX)
     */
    public function getProgramStudies(Faculty $faculty)
    {
        $user = Auth::user();

        // Security check using policy or manual check
        if ($faculty->institution_id !== $user->institution_id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $programStudies = ProgramStudy::where('faculty_id', $faculty->id)
            ->where('university_id', $user->institution_id)
            ->select('id', 'name')
            ->orderBy('name')
            ->get();

        return response()->json($programStudies);
    }
}
