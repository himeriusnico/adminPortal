<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Institution;
use App\Models\User;

class PegawaiFactory extends Factory
{
    public function definition(): array
    {
        return [
            'users_id' => User::factory()->state(['user_type' => 'pegawai']),
            'institution_id' => Institution::factory(),
            'employee_id' => $this->faker->unique()->numerify('NIP-##########'),
            'position' => $this->faker->randomElement(['Dekan', 'Kepala Prodi', 'Staf Akademik']),
            'department' => $this->faker->randomElement(['Fakultas Teknik', 'Akademik Pusat']),
            'status' => 'active',
        ];
    }
}