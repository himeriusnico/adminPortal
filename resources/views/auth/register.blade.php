@extends('layouts.app')

@section('title', 'Register - Repositori Dokumen Akademik')

@section('content')
    <div class="auth-container">
        <div class="auth-wrapper register-page">
            <!-- Logo dan Judul -->
            <div class="auth-logo-section">
                <div class="login-logo">
                    <i class="bi bi-mortarboard-fill"></i>
                </div>
                <h2 class="login-title">Repositori Dokumen Akademik</h2>
                <p class="login-subtitle">Sistem Verifikasi Blockchain</p>
            </div>

            <!-- Kartu Form Registrasi -->
            <div class="auth-card">
                <div class="auth-header register">
                    <h4><i class="bi bi-person-plus"></i> Registrasi Akun Baru</h4>
                </div>

                <div class="auth-body">
                    <form method="POST" action="{{ route('register') }}" class="auth-form">
                        @csrf

                        <!-- Nama -->
                        <div class="form-group">
                            <label for="name" class="form-label">Nama Lengkap</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"
                                name="name" value="{{ old('name') }}" required autofocus>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div class="form-group">
                            <label for="email" class="form-label">Alamat Email</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email"
                                name="email" value="{{ old('email') }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Password -->
                        <div class="form-group">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror"
                                id="password" name="password" required>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Konfirmasi Password -->
                        <div class="form-group">
                            <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                            <input type="password" class="form-control" id="password_confirmation"
                                name="password_confirmation" required>
                        </div>

                        <!-- Dropdown Institusi -->
                        <div class="form-group">
                            <label for="institution_id" class="form-label">Institusi</label>
                            <select class="form-control @error('institution_id') is-invalid @enderror" id="institution_id"
                                name="institution_id" required>
                                <option value="" disabled selected>-- Pilih Institusi Anda --</option>
                                @foreach ($institutions as $institution)
                                    <option value="{{ $institution->id }}"
                                        {{ old('institution_id') == $institution->id ? 'selected' : '' }}>
                                        {{ $institution->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('institution_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Dropdown Posisi -->
                        <div class="form-group">
                            <label for="position" class="form-label">Posisi / Jabatan</label>
                            <select class="form-control @error('position') is-invalid @enderror" id="position"
                                name="position" required>
                                <option value="" disabled selected>-- Pilih Posisi Anda --</option>
                                <option value="Staf Akademik" {{ old('position') == 'Staf Akademik' ? 'selected' : '' }}>
                                    Staf Akademik</option>
                                <option value="Dekan" {{ old('position') == 'Dekan' ? 'selected' : '' }}>Dekan</option>
                                <option value="Kepala Prodi" {{ old('position') == 'Kepala Prodi' ? 'selected' : '' }}>
                                    Kepala Prodi</option>
                            </select>
                            @error('position')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Tombol Submit -->
                        <button type="submit" class="auth-btn auth-btn-success">
                            <i class="bi bi-person-plus"></i> Daftar
                        </button>

                        <!-- Link ke Login -->
                        <div class="auth-footer register">
                            <p>Sudah punya akun? <a href="{{ route('login') }}">Login di sini</a></p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
    