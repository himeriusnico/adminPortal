<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\InstitutionController;
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

    // Students Routes
    Route::get('/students', [StudentController::class, 'index'])->name('students.index');
    Route::get('/students/create', [StudentController::class, 'create'])->name('students.create');
    Route::post('/students', [StudentController::class, 'store'])->name('students.store');
    Route::get('/students/{student}', [StudentController::class, 'show'])->name('students.show');
    Route::get('/students/{student}/edit', [StudentController::class, 'edit'])->name('students.edit');
    Route::put('/students/{student}', [StudentController::class, 'update'])->name('students.update');
    Route::delete('/students/{student}', [StudentController::class, 'destroy'])->name('students.destroy');

    // Institutions Routes - Manual Definition
    Route::get('/institutions', [InstitutionController::class, 'index'])->name('institutions.index');
    Route::post('/institutions', [InstitutionController::class, 'store'])->name('institutions.store');
    Route::get('/institutions/{institution}', [InstitutionController::class, 'show'])->name('institutions.show');
    Route::put('/institutions/{institution}', [InstitutionController::class, 'update'])->name('institutions.update');
    Route::delete('/institutions/{institution}', [InstitutionController::class, 'destroy'])->name('institutions.destroy');

    // Logout
    Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');

    // Route lainnya...
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
