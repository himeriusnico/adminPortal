<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

use App\Models\Document;
use App\Models\Student;
use App\Models\Institution;
use App\Models\User;
use App\Models\Role;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // MATIKAN FK CHECKS
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // BERSIHKAN TABEL
        Document::truncate();
        Student::truncate();
        Institution::truncate();
        User::truncate();
        Role::truncate();
        // Tambahkan tabel lain kalau ada
        // Transaction::truncate();

        // HIDUPKAN FK CHECKS LAGI
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // JALANKAN SEEDER
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
