<?php

namespace Database\Seeders;

use Dom\Document;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            RoleSeeder::class,
            InstitutionSeeder::class,
            FacultySeeder::class,
            ProgramStudySeeder::class,
            UserSeeder::class,
            StudentSeeder::class,
            DocumentTypeSeeder::class,
            DocumentSeeder::class,
        ]);
    }
}
