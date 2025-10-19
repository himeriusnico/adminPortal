@extends('layouts.app')

@section('title', 'Manajemen Mahasiswa - Repositori Dokumen Akademik')

@section('styles')
    <style>
        .student-photo {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
        }

        .status-badge {
            font-size: 0.75rem;
        }

        .action-buttons .btn {
            margin-right: 0.25rem;
        }

        .table-responsive {
            border-radius: 0.5rem;
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                @yield('student-content')
            </div>
        </div>
    </div>
@endsection
