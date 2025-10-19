<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Institution;
use App\Models\User;

class StudentFactory extends Factory
{
    public function definition(): array
    {
        $entryYear = $this->faker->numberBetween(2018, 2022);
        return [
            'user_id' => User::factory()->state(['user_type' => 'student']),
            'institution_id' => Institution::factory(),
            'student_id' => $entryYear . $this->faker->unique()->numerify('######'),
            'phone' => $this->faker->phoneNumber(),
            'program_study' => $this->faker->randomElement(['Teknik Informatika', 'Sistem Informasi', 'Desain Komunikasi Visual']),
            'faculty' => $this->faker->randomElement(['Fakultas Teknologi Industri', 'Fakultas Ilmu Komputer']),
            'entry_year' => $entryYear,
            'status' => $this->faker->randomElement(['active', 'graduated']),
            'graduation_date' => $this->faker->optional()->date(),
        ];
    }
}