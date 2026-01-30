@extends('layouts.app')

@section('title', 'Dashboard - Repositori Dokumen Akademik')

@section('content')
    <div class="dashboard-container">
        <div
            class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-4 pb-3 mb-4 border-bottom">
            <h1 class="h2">Dashboard</h1>
            {{-- <div class="btn-toolbar mb-2 mb-md-0">
                <div class="btn-group me-2">
                    <button type="button" class="btn btn-sm btn-outline-secondary" onclick="window.location.reload()">
                        <i class="bi bi-arrow-clockwise"></i> Refresh
                    </button>
                    <button type="button" class="btn btn-danger" id="swalTestBtn">
                        <i class="bi bi-bell"></i> Test SweetAlert
                    </button>

                </div>
            </div> --}}
        </div>

        <div class="alert alert-info mb-5">
            <h4 class="alert-heading">Selamat Datang, {{ Auth::user()->name }}!</h4>
            <p class="mb-0">

                @if (Auth::user()->role->name === 'super_admin')
                    <i class="bi bi-gem me-2"></i>Anda login sebagai <strong>Super Administrator</strong>.
                @elseif(Auth::user()->role->name === 'admin')
                    <i class="bi bi-shield-check me-2"></i>Anda login sebagai <strong>Administrator</strong>.
                    @if (Auth::user()->institution)
                        dari <strong>{{ Auth::user()->institution->name }}</strong>
                    @endif
                @elseif(Auth::user()->role->name === 'student')
                    <i class="bi bi-person me-2"></i>Anda login sebagai <strong>Mahasiswa</strong>.

                    @if (isset($stats['student_id']))
                        NIM: <strong>{{ $stats['student_id'] }}</strong>
                        @if (isset($stats['program_study']))
                            - {{ $stats['program_study'] }} - {{ $stats['faculty'] }}
                        @endif
                    @endif

                @endif
            </p>
        </div>

        <div class="row mb-0">

            @if (Auth::user()->role->name === 'super_admin')
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card stats-card bg-primary text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h5 class="card-title">Institusi</h5>
                                    <h2 class="mb-0">{{ $stats['total_institutions'] ?? 0 }}</h2>
                                </div>
                                <div class="align-self-center">
                                    <i class="bi bi-building display-6"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card stats-card bg-success text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h5 class="card-title">Admin Institusi</h5>
                                    {{-- Pastikan controller Anda mengirim $stats['total_admins'] --}}
                                    <h2 class="mb-0">{{ $stats['total_admins'] ?? 0 }}</h2>
                                </div>
                                <div class="align-self-center">
                                    <i class="bi bi-people display-6"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card stats-card bg-info text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h5 class="card-title">Mahasiswa</h5>
                                    <h2 class="mb-0">{{ $stats['total_students'] ?? 0 }}</h2>
                                </div>
                                <div class="align-self-center">
                                    <i class="bi bi-person display-6"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card stats-card bg-warning text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h5 class="card-title">Dokumen</h5>
                                    <h2 class="mb-0">{{ $stats['total_documents'] ?? 0 }}</h2>
                                </div>
                                <div class="align-self-center">
                                    <i class="bi bi-file-earmark-text display-6"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @elseif(Auth::user()->role->name === 'admin')
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card stats-card bg-primary text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h5 class="card-title">Total Dokumen</h5>
                                    <h2 class="mb-0">{{ $stats['my_uploads'] ?? 0 }}</h2>
                                </div>
                                <div class="align-self-center">
                                    <i class="bi bi-upload display-6"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card stats-card bg-success text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h5 class="card-title">Terverifikasi</h5>
                                    <h2 class="mb-0">{{ $stats['verified_docs'] ?? 0 }}</h2>
                                </div>
                                <div class="align-self-center">
                                    <i class="bi bi-check-circle display-6"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card stats-card bg-warning text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h5 class="card-title">Menunggu</h5>
                                    <h2 class="mb-0">{{ $stats['pending_docs'] ?? 0 }}</h2>
                                </div>
                                <div class="align-self-center">
                                    <i class="bi bi-clock display-6"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card stats-card bg-info text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h5 class="card-title">Mahasiswa</h5>
                                    <h2 class="mb-0">{{ $stats['total_students'] ?? 0 }}</h2>
                                </div>
                                <div class="align-self-center">
                                    <i class="bi bi-person display-6"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @elseif(Auth::user()->role->name === 'student')
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card stats-card bg-primary text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h5 class="card-title">Dokumen Saya</h5>
                                    <h2 class="mb-0">{{ $stats['my_documents'] ?? 0 }}</h2>
                                </div>
                                <div class="align-self-center">
                                    <i class="bi bi-file-earmark-text display-6"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card stats-card bg-success text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h5 class="card-title">Terverifikasi</h5>
                                    <h2 class="mb-0">{{ $stats['verified_docs'] ?? 0 }}</h2>
                                </div>
                                <div class="align-self-center">
                                    <i class="bi bi-check-circle display-6"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card stats-card bg-warning text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h5 class="card-title">Menunggu</h5>
                                    <h2 class="mb-0">{{ $stats['pending_docs'] ?? 0 }}</h2>
                                </div>
                                <div class="align-self-center">
                                    <i class="bi bi-clock display-6"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card stats-card bg-info text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h5 class="card-title">Transaksi</h5>
                                    <h2 class="mb-0">{{ $stats['transactions'] ?? 0 }}</h2>
                                </div>
                                <div class="align-self-center">
                                    <i class="bi bi-link display-6"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        @if (isset($recent_documents) && $recent_documents->count() > 0)
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="bi bi-clock-history me-2"></i>Daftar Dokumen Terbaru
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="documentsTable" class="table table-hover table-striped" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>Nama File</th>
                                            <th>Jenis</th>

                                            @if (Auth::user()->role->name === 'super_admin' || Auth::user()->role->name === 'admin')
                                                <th>Mahasiswa</th>
                                            @endif

                                            @if (Auth::user()->role->name === 'super_admin' || Auth::user()->role->name === 'student')
                                                <th>Pengunggah (Institusi)</th>
                                            @endif

                                            <th>Tanggal Upload</th>
                                            <th>Status</th>
                                            {{-- <th>TX ID Blockchain</th> --}}
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($recent_documents as $document)
                                            <tr>
                                                <td>
                                                    <i class="bi bi-file-pdf me-2 text-danger"></i>
                                                    {{ $document->filename }}
                                                </td>
                                                <td>
                                                    @if ($document->document_type_id == 1)
                                                        <span class="badge bg-primary">Ijazah</span>
                                                    @elseif($document->document_type_id == 2)
                                                        <span class="badge bg-info">Transkrip</span>
                                                    @else
                                                        <span class="badge bg-success">SKPI</span>
                                                    @endif
                                                </td>

                                                @if (Auth::user()->role->name === 'super_admin' || Auth::user()->role->name === 'admin')
                                                    <td>{{ $document->student->user->name ?? 'N/A' }}</td>
                                                @endif

                                                @if (Auth::user()->role->name === 'super_admin' || Auth::user()->role->name === 'student')
                                                    {{-- Mengganti $document->pegawai->user->name yg error --}}
                                                    <td>{{ $document->institution->name ?? 'N/A' }}</td>
                                                @endif

                                                <td data-order="{{ $document->created_at->timestamp }}">
                                                    {{ $document->created_at->format('d M Y') }}
                                                </td>
                                                <td>
                                                    @if ($document->tx_id)
                                                        <span class="badge bg-success">Terverifikasi</span>
                                                    @else
                                                        <span class="badge bg-warning">Menunggu</span>
                                                    @endif
                                                </td>
                                                {{-- <td>
                                                    @if ($document->tx_id)
                                                    <code title="{{ $document->tx_id }}">
                                                                                                                        {{ Str::limit($document->tx_id, 10) }}
                                                                                                                    </code>
                                                    @else
                                                    <code>-</code>
                                                    @endif
                                                </td> --}}
                                                <td>
                                                    @if ($document->tx_id)
                                                        <button class="btn btn-outline-info btn-sm"
                                                            onclick="showTxId('{{ $document->tx_id }}')" title="Lihat Transaction ID">
                                                            <i class="bi bi-link-45deg"></i> TX ID
                                                        </button>
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body text-center py-5">
                            <i class="bi bi-inbox display-1 text-muted"></i>
                            <h5 class="mt-3">Belum ada dokumen</h5>
                            <p class="text-muted">Tidak ada data dokumen yang ditemukan.</p>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>

    @php
        // Logika untuk menghitung indeks kolom dinamis
        $dateColumnIndex = 2; // Mulai setelah [Nama File, Jenis]
        $actionColumnIndex = 5; // Mulai setelah [Nama, Jenis, Tanggal, Status, TX ID]

        $role = Auth::user()->role->name;

        if ($role === 'super_admin') {
            $dateColumnIndex = 4;   // Tanggal
            $actionColumnIndex = 6; // Aksi
        } elseif ($role === 'admin') {
            $dateColumnIndex = 3;
            $actionColumnIndex = 5;
        } elseif ($role === 'student') {
            $dateColumnIndex = 3;
            $actionColumnIndex = 5;
        }
    @endphp

    @push('scripts')
        <script>
            // $(document).ready(function() {
            //     // Inisialisasi DataTables
            //     $('#documentsTable').DataTable({
            //         language: {
            //             search: "Cari:",
            //             lengthMenu: "Tampilkan _MENU_ data per halaman",
            //             info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
            //             infoEmpty: "Menampilkan 0 sampai 0 dari 0 data",
            //             infoFiltered: "(disaring dari _MAX_ total data)",
            //             zeroRecords: "Tidak ada data yang ditemukan",
            //             paginate: {
            //                 first: "Pertama",
            //                 last: "Terakhir",
            //                 next: "Berikutnya",
            //                 previous: "Sebelumnya"
            //             }
            //         },
            //         order: [
            //             [{{ $dateColumnIndex }}, 'desc']
            //         ],
            //         pageLength: 10,
            //         lengthMenu: [
            //             [5, 10, 25, 50, -1],
            //             [5, 10, 25, 50, "All"]
            //         ],
            //         columnDefs: [{
            //                 // targets: [6], // Kolom aksi
            //                 targets: [{{ $actionColumnIndex }}],
            //                 orderable: false,
            //                 searchable: false
            //             },
            //             {
            //                 targets: '_all',
            //                 className: 'align-middle'
            //             }
            //         ]
            //     });
            // });

            $(document).ready(function () {
                initDataTable('#documentsTable', {
                    order: [
                        [{{ $dateColumnIndex }}, 'desc']
                    ], // Dynamic column sorting
                    columnDefs: [{
                        targets: [{{ $actionColumnIndex }}], // Action column
                        orderable: false,
                        searchable: false
                    },
                    {
                        targets: '_all',
                        className: 'align-middle'
                    }
                    ],
                    pageLength: 10,
                    lengthMenu: [
                        [5, 10, 25, 50, -1],
                        [5, 10, 25, 50, "Semua"]
                    ]
                });
            })

            document.addEventListener("DOMContentLoaded", function () {
                // SweetAlert2 Test Button
                document.getElementById("swalTestBtn").addEventListener("click", function () {
                    Swal.fire({
                        title: "SweetAlert2 Berhasil!",
                        text: "Jika kamu melihat ini, berarti integrasi SweetAlert2 sudah bekerja 🚀",
                        icon: "success",
                        confirmButtonText: "Keren!",
                        confirmButtonColor: "#3085d6",
                    });
                });
            });

            function showTxId(txId) {
                Swal.fire({
                    title: 'Transaction ID Blockchain',
                    html: `<code style="font-size:14px; word-break:break-all;">${txId}</code>`,
                    icon: 'info',
                    confirmButtonText: 'Tutup'
                });
            }
        </script>
    @endpush

@endsection