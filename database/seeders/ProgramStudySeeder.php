<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ProgramStudy;
use App\Models\Faculty;

class ProgramStudySeeder extends Seeder
{
    public function run()
    {
        // Ambil fakultas berdasarkan nama agar tidak tergantung ID angka
        $fikom = Faculty::where('name', 'Fakultas Ilmu Komputer')->first();
        $ekonomi = Faculty::where('name', 'Fakultas Ekonomi')->first();
        $teknik = Faculty::where('name', 'Fakultas Teknik')->first();
        $mipa = Faculty::where('name', 'Fakultas MIPA')->first();

        // Pastikan semua faculty ditemukan
        if (!$fikom || !$ekonomi || !$teknik || !$mipa) {
            throw new \Exception("FacultySeeder belum lengkap atau ada fakultas yang tidak ditemukan.");
        }

        $programs = [
            ['faculty_id' => $fikom->id, 'university_id' => $fikom->institution_id, 'name' => 'Informatika'],
            ['faculty_id' => $fikom->id, 'university_id' => $fikom->institution_id, 'name' => 'Sistem Informasi'],
            ['faculty_id' => $ekonomi->id, 'university_id' => $ekonomi->institution_id, 'name' => 'Manajemen'],
            ['faculty_id' => $teknik->id, 'university_id' => $teknik->institution_id, 'name' => 'Teknik Elektro'],
            ['faculty_id' => $mipa->id, 'university_id' => $mipa->institution_id, 'name' => 'Fisika'],
        ];

        foreach ($programs as $program) {
            ProgramStudy::create([
                'faculty_id' => $program['faculty_id'],
                'university_id' => $program['university_id'],
                'name' => $program['name'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
