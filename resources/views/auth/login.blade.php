@extends('layouts.app')

@section('title', 'Login - Repositori Dokumen Akademik')

@section('content')
    <div class="auth-container">
        <div class="auth-wrapper">

            <div class="auth-logo-section">
                <div class="login-logo">
                    <i class="bi bi-mortarboard-fill"></i>
                </div>
                <h2 class="login-title">Repositori Dokumen Akademik</h2>
                <p class="login-subtitle">Sistem Verifikasi Blockchain</p>
            </div>

            <div class="auth-card">
                <div class="auth-header">
                    <h4><i class="bi bi-box-arrow-in-right"></i>Login ke Sistem</h4>
                </div>
                <div class="auth-body">
                    <form method="POST" action="{{ route('login') }}" class="auth-form">
                        @csrf

                        <!-- Email -->
                        <div class="form-group">
                            <label for="email" class="form-label">Alamat Email</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email"
                                name="email" value="{{ old('email') }}" required autofocus>
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

                        <!-- Remember Me -->
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="remember" name="remember">
                            <label class="form-check-label" for="remember">Ingat Saya</label>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" class="auth-btn auth-btn-primary">
                            <i class="bi bi-box-arrow-in-right"></i>Login
                        </button>

                        <!-- Register Link -->
                        {{-- <div class="auth-footer">
                            <p>Belum punya akun? <a href="{{ route('register') }}">Daftar di sini</a></p>
                        </div> --}}
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection