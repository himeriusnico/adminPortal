<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Document;
use App\Models\Student;
use App\Models\Pegawai;
use App\Models\Institution;
use App\Models\User;

class DashboardController extends Controller
{
  public function index()
  {
    $user = Auth::user();
    $data = [
      'user' => $user,
      'user_type' => $user->user_type,
    ];

    // Tambahkan data real berdasarkan role
    switch ($user->user_type) {
      case 'admin':
        $data['stats'] = $this->getAdminStats();
        $data['recent_documents'] = $this->getRecentDocuments();
        break;
      case 'pegawai':
        $data['stats'] = $this->getPegawaiStats($user->id);
        $data['recent_documents'] = $this->getPegawaiRecentDocuments($user->id);
        break;
      case 'student':
        $data['stats'] = $this->getStudentStats($user->id);
        $data['recent_documents'] = $this->getStudentRecentDocuments($user->id);
        break;
    }

    return view('dashboard', $data);
  }

  private function getAdminStats()
  {
    return [
      'total_institutions' => Institution::count(),
      'total_pegawai' => Pegawai::count(),
      'total_students' => Student::count(),
      'total_documents' => Document::count(),
      'verified_documents' => Document::whereNotNull('tx_id')->count(),
      'pending_documents' => Document::whereNull('tx_id')->count(),
    ];
  }

  private function getPegawaiStats($userId)
  {
    $pegawai = Pegawai::where('users_id', $userId)->first();

    if (!$pegawai) {
      return [
        'my_uploads' => 0,
        'verified_docs' => 0,
        'pending_docs' => 0,
        'total_students' => 0,
      ];
    }

    return [
      'my_uploads' => Document::where('pegawais_id', $pegawai->id)->count(),
      'verified_docs' => Document::where('pegawais_id', $pegawai->id)->whereNotNull('tx_id')->count(),
      'pending_docs' => Document::where('pegawais_id', $pegawai->id)->whereNull('tx_id')->count(),
      'total_students' => Student::where('institution_id', $pegawai->institution_id)->count(),
      'institution_name' => $pegawai->institution->name ?? 'Unknown',
    ];
  }

  private function getStudentStats($userId)
  {
    $student = Student::where('user_id', $userId)->first();

    if (!$student) {
      return [
        'my_documents' => 0,
        'verified_docs' => 0,
        'pending_docs' => 0,
        'transactions' => 0,
      ];
    }

    return [
      'my_documents' => Document::where('student_id', $student->id)->count(),
      'verified_docs' => Document::where('student_id', $student->id)->whereNotNull('tx_id')->count(),
      'pending_docs' => Document::where('student_id', $student->id)->whereNull('tx_id')->count(),
      'transactions' => Document::where('student_id', $student->id)->whereNotNull('tx_id')->count(),
      'student_id' => $student->student_id,
      'program_study' => $student->program_study,
      'faculty' => $student->faculty,
    ];
  }

  private function getRecentDocuments($limit = 5)
  {
    return Document::with(['student.user', 'pegawai.user', 'institution'])
      ->orderBy('created_at', 'desc')
      ->limit($limit)
      ->get();
  }

  private function getPegawaiRecentDocuments($userId, $limit = 5)
  {
    $pegawai = Pegawai::where('users_id', $userId)->first();

    if (!$pegawai) {
      return collect();
    }

    return Document::with(['student.user', 'institution'])
      ->where('pegawais_id', $pegawai->id)
      ->orderBy('created_at', 'desc')
      ->limit($limit)
      ->get();
  }

  private function getStudentRecentDocuments($userId, $limit = 5)
  {
    $student = Student::where('user_id', $userId)->first();

    if (!$student) {
      return collect();
    }

    return Document::with(['pegawai.user', 'institution'])
      ->where('student_id', $student->id)
      ->orderBy('created_at', 'desc')
      ->limit($limit)
      ->get();
  }
}
