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
        // Ambil semua users yang role student (akan kita generate sendiri)
        for ($i = 1; $i <= 10; $i++) {
            $user = User::create([
                'name' => $faker->name,
                'email' => "student{$i}@example.com",
                'password' => bcrypt('password'),
                'role_id' => 2,
                'institution_id' => $i,
            ]);

            Student::create([
                'user_id' => $user->id,
                'institution_id' => $i,
                'student_id' => '2401' . str_pad($i, 4, '0', STR_PAD_LEFT),
                'faculty_id' => $i % 5 + 1,
                'program_study_id' => $i % 5 + 1,
                'phone' => $faker->phoneNumber,
                'entry_year' => $faker->year($max = 'now'),
                'status' => 'active',
            ]);
        }
    }
}
