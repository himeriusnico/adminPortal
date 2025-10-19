<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\StudentController;

use Illuminate\Support\Facades\Route;

// Route untuk tamu
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Autentikasi
Route::get('/register', [RegisterController::class, 'create'])->name('register');
Route::post('/register', [RegisterController::class, 'store']);
Route::get('/login', [LoginController::class, 'create'])->name('login');
Route::post('/login', [LoginController::class, 'store']);

// Route yang perlu login
Route::middleware('auth')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('students', StudentController::class);

    // Logout
    Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');

    // Route berdasarkan role (akan dihandle di controller nanti)
    Route::get('/institutions', function () {
        // Logic untuk cek role ada di controller
        return view('admin.institutions');
    })->name('institutions');

    Route::get('/students', function () {
        return view('admin.students');
    })->name('students');

    Route::get('/documents', function () {
        return view('documents');
    })->name('documents');

    Route::get('/profile', function () {
        return view('profile');
    })->name('profile');
});

// Verifikasi publik
Route::get('/verify', function () {
    return view('public.verify');
})->name('public.verify');
