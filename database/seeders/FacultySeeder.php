<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Faculty;
use App\Models\Institution;

class FacultySeeder extends Seeder
{
    public function run()
    {
        // Ambil institusi sesuai nama dari InstitutionSeeder
        $utn = Institution::where('name', 'Universitas Teknologi Nusantara')->first();
        $pdi = Institution::where('name', 'Politeknik Digital Indonesia')->first();

        // Pastikan ketemu semua
        if (!$utn || !$pdi) {
            throw new \Exception("InstitutionSeeder belum jalan atau data tidak ditemukan.");
        }

        $faculties = [
            ['institution_id' => $utn->id, 'name' => 'Fakultas Ilmu Komputer'],
            ['institution_id' => $utn->id, 'name' => 'Fakultas Ekonomi'],
            ['institution_id' => $pdi->id, 'name' => 'Fakultas Teknik'],
            ['institution_id' => $pdi->id, 'name' => 'Fakultas MIPA'],
            ['institution_id' => $pdi->id, 'name' => 'Fakultas Hukum'],
        ];

        foreach ($faculties as $faculty) {
            Faculty::create([
                'institution_id' => $faculty['institution_id'],
                'name' => $faculty['name'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
