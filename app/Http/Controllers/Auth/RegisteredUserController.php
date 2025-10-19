<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class RegisteredUserController extends Controller
{
  /**
   * Menampilkan halaman/view registrasi.
   *
   * @return \Illuminate\View\View
   */
  public function create(): View
  {
    // Method ini hanya akan menampilkan file view yang akan dibuat oleh AI lain
    return view('auth.register');
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
    // 1. Validasi data yang dikirim dari form
    $request->validate([
      'name' => ['required', 'string', 'max:255'],
      'email' => ['required', 'string', 'email', 'max:255', 'unique:' . User::class],
      'password' => ['required', 'confirmed', Rules\Password::defaults()],
    ]);

    // 2. Buat user baru di database
    $user = User::create([
      'name' => $request->name,
      'email' => $request->email,
      'password' => Hash::make($request->password),
      'user_type' => 'student', // Semua user yang mendaftar default-nya adalah 'student'
    ]);

    // 3. Langsung login-kan user yang baru mendaftar
    Auth::login($user);

    // 4. Arahkan ke halaman dashboard setelah berhasil
    return redirect()->route('dashboard');
  }
}
