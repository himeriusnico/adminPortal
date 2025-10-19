<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class InstitutionFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => $this->faker->company() . ' University',
            'email' => $this->faker->unique()->safeEmail(),
            'public_key' => Str::random(64), // Placeholder untuk public key
            'ca_cert' => Str::random(256), // Placeholder untuk sertifikat
        ];
    }
}
