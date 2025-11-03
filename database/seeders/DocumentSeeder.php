<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Student;
use App\Models\Document;
use Faker\Factory as Faker;
use Illuminate\Support\Str;

class DocumentSeeder extends Seeder
{
  public function run()
  {
    $faker = Faker::create();

    // Ambil semua mahasiswa
    $students = Student::all();

    $documentTypes = ['dokumen_ijazah', 'transkrip', 'skpi'];

    foreach ($students as $student) {
      // Buat 3 dokumen per mahasiswa
      for ($i = 1; $i <= 3; $i++) {
        Document::create([
          'student_id' => $student->id,
          'institution_id' => $student->institution_id,
          'filename' => $faker->word . "_{$i}.pdf",
          'document_type' => $documentTypes[array_rand($documentTypes)],
          // 'tx_id' => $faker->boolean(50) ? $faker->uuid : null, // 50% terverifikasi
          'tx_id' => $faker->uuid, // semua dokumen punya tx_id
          'hash' => Str::uuid(),
          'signature' => Str::uuid(),
          'created_at' => now()->subDays(rand(0, 30)),
          'updated_at' => now()->subDays(rand(0, 30)),
        ]);
      }
    }
  }
}
