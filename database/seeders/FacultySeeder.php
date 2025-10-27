<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Faculty;

class FacultySeeder extends Seeder
{
    public function run()
    {
        $faculties = [
            ['institution_id' => 1, 'name' => 'Fakultas Ilmu Komputer'],
            ['institution_id' => 1, 'name' => 'Fakultas Ekonomi'],
            ['institution_id' => 2, 'name' => 'Fakultas Teknik'],
            ['institution_id' => 3, 'name' => 'Fakultas MIPA'],
            ['institution_id' => 4, 'name' => 'Fakultas Hukum'],
        ];

        foreach ($faculties as $faculty) {
            Faculty::create(array_merge($faculty, ['created_at' => now(), 'updated_at' => now()]));
        }
    }
}
