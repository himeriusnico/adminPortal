<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Repositori Dokumen Akademik')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,100..1000;1,9..40,100..1000&display=swap"
        rel="stylesheet">

    <!-- Bootstrap CSS Manual -->
    <link href="{{ asset('vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">

    <!-- Bootstrap Icons CDN -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <!-- DataTables CSS Basic -->
    <link href="{{ asset('vendor/datatables/css/dataTables.bootstrap5.min.css') }}" rel="stylesheet">

    <!-- Custom CSS untuk dashboard -->
    @if (request()->is('dashboard') || request()->is('dashboard/*'))
        <link href="{{ asset('css/dashboard.css') }}" rel="stylesheet">
    @endif

    <!-- Custom CSS Global -->
    <style>
        body {
            font-family: 'DM Sans', sans-serif;
        }

        .sidebar {
            min-height: calc(100vh - 56px);
            background-color: #343a40;
        }

        .sidebar .nav-link {
            color: #c2c7d0;
        }

        .sidebar .nav-link:hover {
            color: #fff;
            background-color: #495057;
        }

        .sidebar .nav-link.active {
            color: #fff;
            background-color: #007bff;
        }

        .main-content {
            min-height: calc(100vh - 56px);
            padding: 0 2rem;
        }

        /* Container untuk konten dengan max-width */
        .content-container {
            max-width: 1400px;
            margin: 0 auto;
            width: 100%;
        }

        /* Mobile responsive */
        @media (max-width: 768px) {
            .main-content {
                padding: 0 1rem;
            }
        }

        @media (max-width: 576px) {
            .main-content {
                padding: 0 0.75rem;
            }
        }
    </style>

    @yield('styles')
</head>

<body>
    <!-- Header -->
    @include('layouts.partials.header')

    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar (hanya tampil jika user sudah login) -->
            @auth
                @include('layouts.partials.sidebar')
            @endauth

            <!-- Main Content -->
            <main class="@auth col-md-9 ms-sm-auto col-lg-10 px-md-4 @else col-12 @endauth main-content">
                <div class="content-container">
                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    <!-- Footer -->
    @include('layouts.partials.footer')

    <!-- Bootstrap JS Manual -->
    <script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

    <!-- jQuery (Required for DataTables) -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>

    <!-- DataTables JS Basic -->
    <script src="{{ asset('vendor/datatables/js/jquery.dataTables.min.js') }}"></script>

    <!-- Custom JavaScript -->
    <script>
        // Bootstrap tooltip initialization
        document.addEventListener('DOMContentLoaded', function() {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            });
        });
    </script>

    @stack('scripts')
</body>

</html>
