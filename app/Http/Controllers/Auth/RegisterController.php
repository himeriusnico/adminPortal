<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Pegawai;
use App\Models\Institution;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class RegisterController extends Controller
{
  /**
   * Menampilkan halaman/view registrasi.
   *
   * @return \Illuminate\View\View
   */
  public function create(): View
  {
    // 1. Ambil semua data institusi dari database untuk ditampilkan di dropdown
    $institutions = Institution::orderBy('name', 'asc')->get();

    // 2. Kirim data tersebut ke view 'auth.register'
    return view('auth.register', ['institutions' => $institutions]);
  }

  /**
   * Menangani permintaan registrasi yang masuk.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\RedirectResponse
   *
   * @throws \Illuminate\Validation\ValidationException
   */
  public function store(Request $request): RedirectResponse
  {
    // 1. Validasi data yang dikirim dari form, termasuk input baru
    $request->validate([
      'name' => ['required', 'string', 'max:255'],
      'email' => ['required', 'string', 'email', 'max:255', 'unique:' . User::class],
      'password' => ['required', 'confirmed', Rules\Password::defaults()],
      'institution_id' => ['required', 'integer', 'exists:institutions,id'],
      'position' => ['required', 'string', 'in:Staf Akademik,Dekan,Kepala Prodi'],
    ]);

    // 2. Gunakan DB Transaction untuk memastikan data user & pegawai berhasil dibuat bersamaan.
    // Jika salah satu gagal, keduanya akan dibatalkan (rollback).
    $user = DB::transaction(function () use ($request) {
      // Buat user baru di tabel 'users'
      $newUser = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'user_type' => 'pegawai', // Semua user yang mendaftar melalui form ini adalah 'pegawai'
      ]);

      // Buat data pegawai terkait di tabel 'pegawais'
      Pegawai::create([
        'users_id' => $newUser->id, // Ambil ID dari user yang baru dibuat
        'institution_id' => $request->institution_id,
        'position' => $request->position,
        // Employee ID dibuat unik sementara. Anda bisa menyesuaikan logikanya nanti.
        'employee_id' => 'EMP-' . time() . '-' . $newUser->id,
        'status' => 'pending', // Status default saat registrasi
      ]);

      return $newUser;
    });

    // 3. Langsung login-kan user yang baru mendaftar
    Auth::login($user);

    // 4. Arahkan ke halaman dashboard setelah berhasil
    return redirect()->route('dashboard'); // Atau bisa juga redirect()->intended(route('dashboard'))
  }
}
