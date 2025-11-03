<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Document;
use App\Models\Student;
use App\Models\Institution;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $roleName = $user->role->name ?? 'student';

        $data = [
            'user' => $user,
            'role' => $roleName,
        ];

        switch ($roleName) {
            case 'super_admin':
                $data['stats'] = $this->getSuperAdminStats();
                $data['recent_documents'] = $this->getRecentDocuments();
                break;

            case 'admin':
                $data['stats'] = $this->getAdminStats($user);
                $data['recent_documents'] = $this->getInstitutionDocuments($user->institution_id);
                break;

            case 'student':
            default:
                $data['stats'] = $this->getStudentStats($user);
                $data['recent_documents'] = $this->getStudentRecentDocuments($user);
                break;
        }

        return view('dashboard', $data);
    }

    private function getSuperAdminStats()
    {
        return [
            'total_institutions' => Institution::count(),
            'total_users' => User::count(),
            'total_students' => Student::count(),
            'total_documents' => Document::count(),
            'verified_documents' => Document::whereNotNull('tx_id')->count(),
            'pending_documents' => Document::whereNull('tx_id')->count(),
        ];
    }

    private function getAdminStats($user)
    {
        $institutionId = $user->institution_id;

        // Hitung total admin di institution yang sama (termasuk dirinya sendiri)
        $totalAdmins = User::where('institution_id', $institutionId)
            ->whereHas('role', function ($query) {
                $query->where('name', 'admin');
            })->count();

        return [
            'institution_name' => $user->institution->name ?? '-',
            'total_admins' => $totalAdmins,
            'total_students' => Student::where('institution_id', $institutionId)->count(),
            'total_documents' => Document::where('institution_id', $institutionId)->count(),
            'verified_documents' => Document::where('institution_id', $institutionId)->whereNotNull('tx_id')->count(),
            'pending_documents' => Document::where('institution_id', $institutionId)->whereNull('tx_id')->count(),
        ];
    }

    private function getStudentStats($user)
    {
        $student = Student::where('user_id', $user->id)->first();

        if (!$student) {
            return [
                'my_documents' => 0,
                'verified_docs' => 0,
                'pending_docs' => 0,
            ];
        }

        return [
            'my_documents' => Document::where('student_id', $student->id)->count(),
            'verified_docs' => Document::where('student_id', $student->id)->whereNotNull('tx_id')->count(),
            'pending_docs' => Document::where('student_id', $student->id)->whereNull('tx_id')->count(),
            'student_id' => $student->student_id,
            'program_study' => $student->programStudy->name ?? '-',
            'faculty' => $student->faculty->name ?? '-',
        ];
    }

    private function getRecentDocuments($limit = 5)
    {
        return Document::with(['student.user', 'institution'])
            ->latest()
            ->take($limit)
            ->get();
    }

    private function getInstitutionDocuments($institutionId, $limit = 5)
    {
        return Document::with(['student.user', 'institution'])
            ->where('institution_id', $institutionId)
            ->latest()
            ->take($limit)
            ->get();
    }

    private function getStudentRecentDocuments($user, $limit = 5)
    {
        $student = Student::where('user_id', $user->id)->first();

        if (!$student) {
            return collect();
        }

        return Document::with(['institution'])
            ->where('student_id', $student->id)
            ->latest()
            ->take($limit)
            ->get();
    }
}
