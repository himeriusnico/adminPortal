<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Student;
use App\Models\User;
use App\Models\Role;
use App\Models\Institution;
use Illuminate\Support\Facades\Hash;

class StudentSeeder extends Seeder
{
    public function run()
    {
        // Student::truncate();

        $studentRole = Role::where('name', 'student')->first();

        foreach (Institution::all() as $institution) {
            for ($i = 1; $i <= 5; $i++) {

                // USER mahasiswa
                $user = User::create([
                    'name' => "Mahasiswa {$institution->id} - {$i}",
                    'email' => "student{$institution->id}_{$i}@example.com",
                    'password' => Hash::make('password'),
                    'role_id' => $studentRole->id
                ]);

                // RECORD mahasiswa
                Student::create([
                    'user_id' => $user->id,
                    'institution_id' => $institution->id,
                    'student_id' => "SID{$institution->id}{$i}",
                    'faculty_id' => null, // isi jika sudah punya faculty
                    'program_study_id' => null,
                    'entry_year' => 2023,
                    'status' => 'active'
                ]);
            }
        }
    }
}
