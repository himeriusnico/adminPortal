<?php

namespace Database\Factories;

use App\Models\Institution;
use App\Models\Pegawai;
use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class DocumentFactory extends Factory
{
    public function definition(): array
    {
        return [
            'student_id' => Student::factory(),
            'pegawais_id' => Pegawai::factory(),
            'institution_id' => Institution::factory(),
            'filename' => Str::random(10) . '.pdf',
            'document_type' => $this->faker->randomElement(['dokumen_ijazah', 'transkrip', 'skpi']),
            'hash' => hash('sha256', Str::random(40)),
            'signature' => Str::random(128), // Placeholder tanda tangan digital
            'tx_id' => '0x' . Str::random(64), // Placeholder ID transaksi blockchain
            'file_path' => 'documents/' . Str::random(10) . '.pdf',
        ];
    }
}