<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ProgramStudy;

class ProgramStudySeeder extends Seeder
{
    public function run()
    {
        $programs = [
            ['faculty_id' => 1, 'university_id' => 1, 'name' => 'Informatika'],
            ['faculty_id' => 1, 'university_id' => 1, 'name' => 'Sistem Informasi'],
            ['faculty_id' => 2, 'university_id' => 1, 'name' => 'Manajemen'],
            ['faculty_id' => 3, 'university_id' => 2, 'name' => 'Teknik Elektro'],
            ['faculty_id' => 4, 'university_id' => 3, 'name' => 'Fisika'],
        ];

        foreach ($programs as $program) {
            ProgramStudy::create(array_merge($program, ['created_at' => now(), 'updated_at' => now()]));
        }
    }
}
