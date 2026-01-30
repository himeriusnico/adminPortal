<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Institution;

class InstitutionSeeder extends Seeder
{
    public function run()
    {
        $institutions = [
            [
                'name' => 'Universitas Teknologi Nusantara',
                'email' => 'utn@example.com',
                'alamat' => 'Jakarta',
                'public_key' => 'dummy_public_key',
                'ca_cert' => 'dummy_ca_cert',
                'encrypted_key_id' => null,
            ],
            [
                'name' => 'Politeknik Digital Indonesia',
                'email' => 'pdi@example.com',
                'alamat' => 'Bandung',
                'public_key' => 'dummy_public_key',
                'ca_cert' => 'dummy_ca_cert',
                'encrypted_key_id' => null,
            ],
        ];


        foreach ($institutions as $data) {
            Institution::create($data);
        }
    }
}
