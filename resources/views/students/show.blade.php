@extends('students.layout')

@section('student-content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-2">Detail Mahasiswa</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('students.index') }}">Mahasiswa</a></li>
                    <li class="breadcrumb-item active">{{ $student->user->name }}</li>
                </ol>
            </nav>
        </div>
        {{-- <div>
            <a href="{{ route('students.index') }}" class="btn btn-secondary me-2">
                <i class="bi bi-arrow-left me-2"></i>Kembali
            </a>
            <a href="{{ route('students.edit', $student->id) }}" class="btn btn-warning me-2">
                <i class="bi bi-pencil me-2"></i>Edit
            </a>
            <button class="btn btn-danger" onclick="deleteStudent({{ $student->id }})">
                <i class="bi bi-trash me-2"></i>Hapus
            </button>
        </div> --}}
    </div>

    <div class="row">
        <!-- Profile Card -->
        <div class="col-lg-4 mb-4">
            <div class="card">
                <div class="card-body text-center">
                    <div class="student-photo bg-primary mx-auto mb-3 d-flex align-items-center justify-content-center text-white"
                        style="width: 80px; height: 80px;">
                        <i class="bi bi-person-fill display-6"></i>
                    </div>
                    <h4>{{ $student->user->name }}</h4>
                    <p class="text-muted">{{ $student->user->email }}</p>

                    <div class="mb-3">
                        @if ($student->status === 'active')
                            <span class="badge bg-success status-badge fs-6">Aktif</span>
                        @elseif($student->status === 'graduated')
                            <span class="badge bg-primary status-badge fs-6">Lulus</span>
                        @else
                            <span class="badge bg-warning status-badge fs-6">Non-Aktif</span>
                        @endif
                    </div>

                    <div class="text-start">
                        <p><strong>NIM:</strong> <code>{{ $student->student_id }}</code></p>
                        <p><strong>Program Studi:</strong> {{ $student->programStudy->name ?? 'N/A' }}</p>
                        <p><strong>Fakultas:</strong> {{ $student->faculty->name ?? 'N/A' }}</p>
                        <p><strong>Tahun Masuk:</strong> {{ $student->entry_year }}</p>
                        @if ($student->phone)
                            <p><strong>Telepon:</strong> {{ $student->phone }}</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Documents Card -->
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-file-earmark-text me-2"></i>Dokumen Akademik
                    </h5>
                </div>
                <div class="card-body">
                    @if ($student->documents->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Dokumen</th>
                                        <th>Jenis</th>
                                        <th>Tanggal Upload</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($student->documents as $document)
                                        <tr>
                                            <td>
                                                <i class="bi bi-file-pdf text-danger me-2"></i>
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
                                            <td>{{ $document->created_at->format('d M Y') }}</td>
                                            <td>
                                                @if ($document->tx_id)
                                                    <span class="badge bg-success">Terverifikasi</span>
                                                @else
                                                    <span class="badge bg-warning">Menunggu</span>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('documents.view', $document->id) }}" target="_blank"
                                                    class="btn btn-sm btn-outline-primary" title="Lihat dokumen">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                                {{-- Download e belum ya --}}
                                                <button class="btn btn-sm btn-outline-primary" title="Unduh">
                                                    <i class="bi bi-download"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="bi bi-inbox display-1 text-muted d-block mb-3"></i>
                            <h5 class="text-muted">Belum ada dokumen</h5>
                            <p class="text-muted">Mahasiswa ini belum memiliki dokumen akademik</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            function deleteStudent(id) {
                if (confirm('Apakah Anda yakin ingin menghapus mahasiswa ini?')) {
                    fetch(`/students/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json'
                        }
                    }).then(response => {
                        if (response.ok) {
                            window.location.href = '/students';
                        } else {
                            alert('Gagal menghapus mahasiswa');
                        }
                    });
                }
            }
        </script>
    @endpush
@endsection
