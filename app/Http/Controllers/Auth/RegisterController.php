<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
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
   * Tampilkan halaman registrasi.
   */
  public function create(): View
  {
    $institutions = Institution::orderBy('name', 'asc')->get();
    return view('auth.register', compact('institutions'));
  }

  /**
   * Tangani permintaan registrasi.
   */
  public function store(Request $request): RedirectResponse
  {
    $request->validate([
      'name' => ['required', 'string', 'max:255'],
      'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
      'password' => ['required', 'confirmed', Rules\Password::defaults()],
      'institution_id' => ['required', 'integer', 'exists:institutions,id'],
    ]);

    $user = DB::transaction(function () use ($request) {
      return User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'institution_id' => $request->institution_id,
        'role_id' => 3, // default student
      ]);
    });

    Auth::login($user);

    return redirect()->route('dashboard');
  }
}
