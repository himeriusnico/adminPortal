<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DocumentType;

class DocumentTypeSeeder extends Seeder
{
    public function run()
    {
        $types = ['dokumen_ijazah', 'transkrip', 'skpi'];
        foreach ($types as $type) {
            DocumentType::create(['name' => $type]);
        }
    }
}
