<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use App\Models\Institution;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
  public function run()
  {
    // User::truncate();

    $adminRole = Role::where('name', 'admin')->first();
    $superAdminRole = Role::where('name', 'super_admin')->first();

    // SUPER ADMIN SISTEM
    User::create([
      'name' => 'Super Admin',
      'email' => 'superadmin@example.com',
      'password' => Hash::make('password'),
      'role_id' => $superAdminRole->id
    ]);

    // ADMIN UNTUK SETIAP INSTITUSI
    foreach (Institution::all() as $institution) {
      User::create([
        'name' => $institution->name . ' Admin',
        'email' => 'admin_' . $institution->id . '@example.com',
        'password' => Hash::make('password'),
        'role_id' => $adminRole->id,
        // TIDAK ADA institution_id di tabel USER → relasi ditangani lewat Student
      ]);
    }
  }
}
