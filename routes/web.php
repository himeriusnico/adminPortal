<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\InstitutionController;
use App\Http\Controllers\DocumentController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Di sini route di-adjust untuk mencerminkan 3 role:
| 1. super_admin -> Kelola Institusi, Kelola Pengguna
| 2. admin       -> Kelola Mahasiswa, Kelola Dokumen
| 3. student     -> Lihat Dokumen Saya
|
*/

// == 1. RUTE PUBLIK (TAMU) ==
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Verifikasi publik
Route::get('/verify', function () {
    return view('public.verify');
})->name('public.verify');


// Grup Rute Tamu (Guest)
// Hanya bisa diakses jika BELUM login
Route::middleware('guest')->group(function () {

    // Autentikasi
    Route::get('/register', [RegisterController::class, 'create'])->name('register');
    Route::post('/register', [RegisterController::class, 'store']);
    Route::get('/login', [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'store']);
});


// == 2. RUTE TERAUTENTIKASI (SEMUA ROLE) ==
// (Semua pengguna yang login bisa akses ini)
Route::middleware('auth')->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Logout
    Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');

    // Pengaturan Umum (sesuai sidebar 'settings*')
    Route::get('/settings', function () {
        return view('profile'); // Asumsi 'settings' mengarah ke view 'profile'
    })->name('settings');
});


// == 3. RUTE SUPER ADMIN ==
// (Hanya 'super_admin' yang bisa akses)
Route::middleware(['auth', 'role:super_admin'])->group(function () {

    // Institutions Routes (sesuai file asli Anda)
    Route::get('/institutions', [InstitutionController::class, 'index'])->name('institutions.index');
    Route::post('/institutions', [InstitutionController::class, 'store'])->name('institutions.store');
    Route::get('/institutions/{institution}', [InstitutionController::class, 'show'])->name('institutions.show');
    Route::put('/institutions/{institution}', [InstitutionController::class, 'update'])->name('institutions.update');
    Route::delete('/institutions/{institution}', [InstitutionController::class, 'destroy'])->name('institutions.destroy');
    // CATATAN: 5 baris di atas bisa disingkat menjadi:
    // Route::resource('institutions', InstitutionController::class)->except(['create', 'edit']);

    // Kelola Pengguna (sesuai sidebar 'users*')
    Route::get('/users', function () {
        // Arahkan ke view atau controller Anda untuk kelola user
        return view('users.index'); // Placeholder
    })->name('users.index');
});


// == 4. RUTE ADMIN ==
// (Hanya 'admin' yang bisa akses)
Route::middleware(['auth', 'role:admin'])->group(function () {

    // Students Routes (sesuai file asli Anda)
    // PASTIKAN SEMUA ROUTE INI ADA DI DALAM GRUP INI
    Route::get('/students', [StudentController::class, 'index'])->name('students.index');
    Route::get('/students/create', [StudentController::class, 'create'])->name('students.create');
    Route::post('/students', [StudentController::class, 'store'])->name('students.store');
    Route::get('/students/{student}/edit', [StudentController::class, 'edit'])->name('students.edit');
    Route::put('/students/{student}', [StudentController::class, 'update'])->name('students.update');
    Route::delete('/students/{student}', [StudentController::class, 'destroy'])->name('students.destroy');

    // Unggah Dokumen (sesuai sidebar 'documents*')
    Route::get('/documents', [DocumentController::class, 'index'])->name('documents.index');
    Route::post('/documents', [DocumentController::class, 'store'])->name('documents.store');

    // Pengaturan Institusi (sesuai sidebar 'institution*')
    Route::get('/institution/settings', function () {
        return view('institution.settings'); // Asumsi ada view settings
    })->name('institution.settings');
});

// == 5. RUTE STUDENT ==
// (Hanya 'student' yang bisa akses)
Route::middleware(['auth', 'role:student'])->group(function () {

    // Dokumen Saya (sesuai sidebar 'my-documents*')
    Route::get('/my-documents', function () {
        return view('student.my-documents'); // Asumsi view
    })->name('my.documents');

    // Verifikasi Dokumen (sesUai sidebar 'verification*')
    Route::get('/verification', function () {
        return view('student.verification'); // Asumsi view
    })->name('verification');
});

Route::middleware(['auth', 'role:admin,student'])->group(function () {

    // Rute 'Dokumen Saya' (untuk student) / 'Detail Mahasiswa' (untuk admin)
    Route::get('/students/{student}', [StudentController::class, 'show'])->name('students.show');
});
// Catatan: Route '/documents' dan '/profile' lama Anda 
// yang ada di grup 'auth' umum telah dihapus/dipindahkan 
// ke dalam grup role masing-masing ('settings' dan 'documents.index').