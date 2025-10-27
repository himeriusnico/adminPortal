<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Institution;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
  public function run(): void
  {
    // 1️⃣ Super Admin
    User::updateOrCreate(
      ['email' => 'superadmin@ta-sukses.id'],
      [
        'name' => 'Super Admin',
        'email_verified_at' => now(),
        'password' => Hash::make('password'),
        'role_id' => 1, // super_admin
        'institution_id' => null,
      ]
    );

    // 2️⃣ Admin per universitas
    $institutions = Institution::all();
    foreach ($institutions as $institution) {
      User::updateOrCreate(
        ['email' => 'admin@' . strtolower(str_replace(' ', '', $institution->name)) . '.ac.id'],
        [
          'name' => 'Admin ' . $institution->name,
          'email_verified_at' => now(),
          'password' => Hash::make('password'),
          'role_id' => 2, // admin
          'institution_id' => $institution->id,
        ]
      );
    }
  }
}
