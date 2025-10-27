<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Institution;

class InstitutionSeeder extends Seeder
{
    public function run()
    {
        $institutions = [
            ['name' => 'Universitas Indonesia', 'email' => 'info@ui.ac.id', 'public_key' => 'fake_public_key_ui', 'ca_cert' => 'fake_ca_ui'],
            ['name' => 'Universitas Gadjah Mada', 'email' => 'info@ugm.ac.id', 'public_key' => 'fake_public_key_ugm', 'ca_cert' => 'fake_ca_ugm'],
            ['name' => 'Institut Teknologi Bandung', 'email' => 'info@itb.ac.id', 'public_key' => 'fake_public_key_itb', 'ca_cert' => 'fake_ca_itb'],
            ['name' => 'Universitas Airlangga', 'email' => 'info@unair.ac.id', 'public_key' => 'fake_public_key_unair', 'ca_cert' => 'fake_ca_unair'],
            ['name' => 'Universitas Diponegoro', 'email' => 'info@undip.ac.id', 'public_key' => 'fake_public_key_undip', 'ca_cert' => 'fake_ca_undip'],
            ['name' => 'Universitas Brawijaya', 'email' => 'info@ub.ac.id', 'public_key' => 'fake_public_key_ub', 'ca_cert' => 'fake_ca_ub'],
            ['name' => 'Universitas Sebelas Maret', 'email' => 'info@uns.ac.id', 'public_key' => 'fake_public_key_uns', 'ca_cert' => 'fake_ca_uns'],
            ['name' => 'Universitas Hasanuddin', 'email' => 'info@unhas.ac.id', 'public_key' => 'fake_public_key_unhas', 'ca_cert' => 'fake_ca_unhas'],
            ['name' => 'Universitas Padjadjaran', 'email' => 'info@unpad.ac.id', 'public_key' => 'fake_public_key_unpad', 'ca_cert' => 'fake_ca_unpad'],
            ['name' => 'Universitas Udayana', 'email' => 'info@unud.ac.id', 'public_key' => 'fake_public_key_unud', 'ca_cert' => 'fake_ca_unud'],
        ];

        foreach ($institutions as $institution) {
            Institution::create(array_merge($institution, ['created_at' => now(), 'updated_at' => now()]));
        }
    }
}
