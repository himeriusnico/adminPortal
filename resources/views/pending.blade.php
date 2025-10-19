@extends('layouts.app') {{-- Asumsi Anda punya layout dasar --}}

@section('title', 'Menunggu Persetujuan')

@section('content')
    <div class="container text-center" style="padding-top: 100px;">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <i class="bi bi-hourglass-split" style="font-size: 4rem; color: #0d6efd;"></i>
                        <h3 class="card-title mt-3">Akun Anda Sedang Ditinjau</h3>
                        <p class="card-text">
                            Terima kasih telah mendaftar. Akun Anda saat ini sedang menunggu konfirmasi
                            dari pihak Admin (Direktorat Admik) institusi Anda.
                        </p>
                        <p>Mohon menunggu...</p>

                        {{-- Opsi untuk Logout --}}
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="btn btn-outline-danger mt-3">Logout</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
