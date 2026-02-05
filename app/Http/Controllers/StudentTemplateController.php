<?php

namespace App\Http\Controllers;

use App\Exports\StudentTemplateExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Auth;

class StudentTemplateController extends Controller
{
    public function download()
    {
        $institution = Auth::user()->institution;

        $filename = 'template_mahasiswa_' . now()->format('Ymd_His') . '.xlsx';

        return Excel::download(
            new StudentTemplateExport(),
            $filename
        );
    }
}
