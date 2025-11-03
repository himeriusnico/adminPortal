<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Student;
use App\Models\User;
use Faker\Factory as Faker;

class StudentSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        // Misal ada 5 institusi
        $totalInstitutions = 5;

        for ($inst = 1; $inst <= $totalInstitutions; $inst++) {
            for ($i = 1; $i <= 5; $i++) { // 5 mahasiswa per institusi
                $user = User::create([
                    'name' => $faker->name,
                    'email' => "student{$inst}_{$i}@example.com",
                    'password' => bcrypt('password'),
                    'role_id' => 3, // student = 3
                    'institution_id' => $inst,
                ]);

                Student::create([
                    'user_id' => $user->id,
                    'institution_id' => $inst,
                    'student_id' => '2401' . str_pad(($inst * 10 + $i), 4, '0', STR_PAD_LEFT),
                    'faculty_id' => $i % 5 + 1,
                    'program_study_id' => $i % 5 + 1,
                    'phone' => $faker->phoneNumber,
                    'entry_year' => $faker->year($max = 'now'),
                    'status' => 'active',
                ]);
            }
        }
    }
}
