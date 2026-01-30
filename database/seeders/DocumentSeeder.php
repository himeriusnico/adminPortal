<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Student;
use App\Models\Document;
use App\Models\DocumentType;
use Faker\Factory as Faker;
use Illuminate\Support\Str;

class DocumentSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        $students = Student::all();
        $documentTypes = DocumentType::pluck('id')->toArray(); // -> [1,2,3]

        foreach ($students as $student) {
            for ($i = 1; $i <= 3; $i++) {
                Document::create([
                    'student_id' => $student->id,
                    'institution_id' => $student->institution_id,
                    'filename' => $faker->word . "_{$i}.pdf",
                    'document_type_id' => $documentTypes[array_rand($documentTypes)],
                    'tx_id' => $faker->uuid,
                    'hash' => Str::uuid(),
                    'signature' => Str::uuid(),
                    'file_path' => null, // opsional, karena tabel punya kolom ini
                    'created_at' => now()->subDays(rand(0, 30)),
                    'updated_at' => now()->subDays(rand(0, 30)),
                ]);
            }
        }
    }
}
