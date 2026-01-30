@extends('layouts.app')

@section('title', 'Manajemen Institusi - Repositori Dokumen Akademik')

@section('styles')
    {{-- <link href="{{ asset('vendor/datatables/css/dataTables.min.css') }}" rel="stylesheet"> --}}
    <style>
        .institution-logo {
            width: 40px;
            height: 40px;
            border-radius: 8px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
        }

        .action-buttons .btn {
            margin-right: 0.25rem;
        }

        .table-responsive {
            border-radius: 0.5rem;
        }

        .stats-card {
            transition: all 0.3s ease;
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            cursor: pointer;
            height: 100%;
        }

        .stats-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.2);
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                @yield('institution-content')
            </div>
        </div>
    </div>
@endsection
