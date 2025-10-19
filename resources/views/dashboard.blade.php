@extends('layouts.app')

@section('title', 'Dashboard - Repositori Dokumen Akademik')

@section('content')
    <!-- Container utama dengan padding -->
    <div class="dashboard-container">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-4 pb-3 mb-4 border-bottom">
            <h1 class="h2">Dashboard</h1>
            <div class="btn-toolbar mb-2 mb-md-0">
                <div class="btn-group me-2">
                    <button type="button" class="btn btn-sm btn-outline-secondary" onclick="window.location.reload()">
                        <i class="bi bi-arrow-clockwise"></i> Refresh
                    </button>
                </div>
            </div>
        </div>

        <!-- Welcome Message -->
        <div class="alert alert-info mb-4">
            <h4 class="alert-heading">Selamat Datang, {{ Auth::user()->name }}!</h4>
            <p class="mb-0">
                @if (Auth::user()->user_type === 'admin')
                    <i class="bi bi-shield-check me-2"></i>Anda login sebagai <strong>Administrator</strong>.
                @elseif(Auth::user()->user_type === 'pegawai')
                    <i class="bi bi-person-badge me-2"></i>Anda login sebagai <strong>Pegawai/Staf Akademik</strong>.
                    @if (isset($stats['institution_name']))
                        dari <strong>{{ $stats['institution_name'] }}</strong>
                    @endif
                @else
                    <i class="bi bi-person me-2"></i>Anda login sebagai <strong>Mahasiswa</strong>.
                    @if (isset($stats['student_id']))
                        NIM: <strong>{{ $stats['student_id'] }}</strong> -
                        {{ $stats['program_study'] }} - {{ $stats['faculty'] }}
                    @endif
                @endif
            </p>
        </div>

        <!-- Quick Stats Cards -->
        <div class="row mb-5">
            @if (Auth::user()->user_type === 'admin')
                <!-- Stats untuk Admin -->
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
                                    <h5 class="card-title">Pegawai</h5>
                                    <h2 class="mb-0">{{ $stats['total_pegawai'] ?? 0 }}</h2>
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
            @elseif(Auth::user()->user_type === 'pegawai')
                <!-- Stats untuk Pegawai -->
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card stats-card bg-primary text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h5 class="card-title">Upload Saya</h5>
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
            @else
                <!-- Stats untuk Student -->
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

        <!-- Recent Documents Table dengan DataTables -->
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
                                            @if (Auth::user()->user_type === 'admin' || Auth::user()->user_type === 'pegawai')
                                                <th>Mahasiswa</th>
                                            @endif
                                            @if (Auth::user()->user_type === 'admin' || Auth::user()->user_type === 'student')
                                                <th>Pegawai</th>
                                            @endif
                                            <th>Tanggal Upload</th>
                                            <th>Status</th>
                                            <th>TX ID Blockchain</th>
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
                                                    @if ($document->document_type === 'dokumen_ijazah')
                                                        <span class="badge bg-primary">Ijazah</span>
                                                    @elseif($document->document_type === 'transkrip')
                                                        <span class="badge bg-info">Transkrip</span>
                                                    @else
                                                        <span class="badge bg-success">SKPI</span>
                                                    @endif
                                                </td>
                                                @if (Auth::user()->user_type === 'admin' || Auth::user()->user_type === 'pegawai')
                                                    <td>{{ $document->student->user->name ?? 'N/A' }}</td>
                                                @endif
                                                @if (Auth::user()->user_type === 'admin' || Auth::user()->user_type === 'student')
                                                    <td>{{ $document->pegawai->user->name ?? 'N/A' }}</td>
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
                                                <td>
                                                    @if ($document->tx_id)
                                                        <code title="{{ $document->tx_id }}">
                                                            {{ Str::limit($document->tx_id, 10) }}
                                                        </code>
                                                    @else
                                                        <code>-</code>
                                                    @endif
                                                </td>
                                                <td>
                                                    <button class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                                        data-bs-target="#qrModal{{ $document->id }}"
                                                        title="Lihat QR Code">
                                                        <i class="bi bi-qr-code"></i> QR
                                                    </button>
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

            <!-- QR Modals -->
            @foreach ($recent_documents as $document)
                <div class="modal fade" id="qrModal{{ $document->id }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">QR Code - {{ $document->filename }}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body text-center">
                                <div class="bg-light p-4 mb-3 rounded">
                                    <i class="bi bi-qr-code display-1 text-muted"></i>
                                    <p class="text-muted mt-2">QR Code akan ditampilkan di sini</p>
                                </div>
                                <small class="text-muted">Fitur QR Code akan diimplementasikan kemudian</small>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-success" disabled>
                                    <i class="bi bi-download"></i> Unduh QR
                                </button>
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                    Tutup
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        @else
            <!-- Empty state -->
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

    @push('scripts')
        <script>
            $(document).ready(function() {
                // Inisialisasi DataTables
                $('#documentsTable').DataTable({
                    language: {
                        search: "Cari:",
                        lengthMenu: "Tampilkan _MENU_ data per halaman",
                        info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                        infoEmpty: "Menampilkan 0 sampai 0 dari 0 data",
                        infoFiltered: "(disaring dari _MAX_ total data)",
                        zeroRecords: "Tidak ada data yang ditemukan",
                        paginate: {
                            first: "Pertama",
                            last: "Terakhir",
                            next: "Berikutnya",
                            previous: "Sebelumnya"
                        }
                    },
                    order: [
                        [3, 'desc']
                    ], // Default urut berdasarkan tanggal upload descending
                    pageLength: 10,
                    lengthMenu: [
                        [5, 10, 25, 50, -1],
                        [5, 10, 25, 50, "All"]
                    ],
                    columnDefs: [{
                            targets: [6], // Kolom aksi
                            orderable: false,
                            searchable: false
                        },
                        {
                            targets: '_all',
                            className: 'align-middle'
                        }
                    ]
                });
            });
        </script>
    @endpush

@endsection
