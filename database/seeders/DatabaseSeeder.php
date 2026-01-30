<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();

        // 1. Buat Roles (Penting untuk pengecekan @if di Blade Anda)
        // Kita simpan ID-nya untuk digunakan saat insert user
        $superAdminRoleId = DB::table('roles')->insertGetId([
            'name'       => 'super_admin',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $adminInstRoleId = DB::table('roles')->insertGetId([
            'name'       => 'admin',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $studentRoleId = DB::table('roles')->insertGetId([
            'name'       => 'student',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 2. Daftar Institusi
        $institutions = [
            ['name' => 'Institut Teknologi Sepuluh Nopember', 'short' => 'its'],
            ['name' => 'Universitas Airlangga', 'short' => 'unair'],
            ['name' => 'Universitas Kristen Petra', 'short' => 'ukp'],
            ['name' => 'Universitas Gadjah Mada', 'short' => 'ugm'],
            ['name' => 'Universitas Indonesia', 'short' => 'ui'],
        ];

        foreach ($institutions as $inst) {
            $instId = DB::table('institutions')->insertGetId([
                'name'       => $inst['name'],
                'email'      => $inst['short'] . '@example.com',
                'public_key' => Str::random(40),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // 3. User Admin Institusi
            DB::table('users')->insert([
                'name'           => 'Admin ' . $inst['name'],
                'email'          => 'admin@' . $inst['short'] . '.ac.id',
                'password'       => Hash::make('password'),
                'role_id'        => $adminInstRoleId,
                'institution_id' => $instId,
                'created_at'     => now(),
                'updated_at'     => now(),
            ]);
        }

        // 4. Masukkan Super Admin Utama
        DB::table('users')->insert([
            'name'           => 'Super Admin',
            'email'          => 'superadmin@example.com',
            'password'       => '$2y$10$xW0TuEEwKvqAXseBGxl6U.MOmVVOvWC8JKhDXQXr.19CsPqxQMx0W',
            'role_id'        => $superAdminRoleId, // Terhubung ke role 'super_admin'
            'institution_id' => null,
            'created_at'     => now(),
            'updated_at'     => now(),
        ]);

        Schema::enableForeignKeyConstraints();
    }
}