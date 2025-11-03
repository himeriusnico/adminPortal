@extends('layouts.app')

@section('title', 'Unggah Dokumen - Repositori Dokumen Akademik')

@section('content')
    <div class="d-flex justify-content-between align-items-center pt-4 pb-3 mb-4 border-bottom">
        <h1 class="h2">Manajemen Dokumen</h1>
    </div>

    {{-- Tampilkan Pesan Sukses/Error --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Gagal!</strong> Terjadi kesalahan validasi:
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif


    <div class="card mb-4">
        <div class="card-header">
            <h5 class="card-title mb-0"><i class="bi bi-upload me-2"></i>Unggah Dokumen Baru</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('documents.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-md-5 mb-3">
                        <label for="student_id" class="form-label">Mahasiswa *</label>
                        <select class="form-select @error('student_id') is-invalid @enderror" id="student_id"
                            name="student_id" required>
                            <option value="" disabled selected>-- Pilih Mahasiswa --</option>
                            @forelse ($students as $student)
                                <option value="{{ $student->id }}"
                                    {{ old('student_id') == $student->id ? 'selected' : '' }}>
                                    {{ $student->user->name ?? 'User Hilang' }} (NIM: {{ $student->student_id }})
                                </option>
                            @empty
                                <option value="" disabled>Belum ada data mahasiswa di institusi Anda</option>
                            @endforelse
                        </select>
                    </div>

                    <div class="col-md-3 mb-3">
                        <label for="document_type" class="form-label">Jenis Dokumen *</label>
                        <select class="form-select @error('document_type') is-invalid @enderror" id="document_type"
                            name="document_type" required>
                            <option value="" disabled selected>-- Pilih Jenis --</option>
                            {{-- (Ini berdasarkan ENUM di tabel Dokumen Anda) --}}
                            <option value="dokumen_ijazah" {{ old('document_type') == 'dokumen_ijazah' ? 'selected' : '' }}>
                                Ijazah</option>
                            <option value="transkrip" {{ old('document_type') == 'transkrip' ? 'selected' : '' }}>Transkrip
                                Nilai</option>
                            <option value="skpi" {{ old('document_type') == 'skpi' ? 'selected' : '' }}>SKPI</option>
                        </select>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="file" class="form-label">File PDF (Maks 2MB) *</label>
                        <input class="form-control @error('file') is-invalid @enderror" type="file" id="file"
                            name="file" accept=".pdf" required>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-upload me-2"></i>Unggah
                </button>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0"><i class="bi bi-list-ul me-2"></i>Dokumen Terunggah (Institusi Anda)</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="documentsTable" class="table table-hover table-striped" style="width:100%">
                    <thead>
                        <tr>
                            <th>Mahasiswa</th>
                            <th>NIM</th>
                            <th>Jenis Dokumen</th>
                            <th>Nama File</th>
                            <th>Tgl. Upload</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($documents as $doc)
                            <tr>
                                <td>{{ $doc->student->user->name ?? 'N/A' }}</td>
                                <td><code>{{ $doc->student->student_id ?? 'N/A' }}</code></td>
                                <td>
                                    @if ($doc->document_type === 'dokumen_ijazah')
                                        <span class="badge bg-primary">Ijazah</span>
                                    @elseif($doc->document_type === 'transkrip')
                                        <span class="badge bg-info">Transkrip</span>
                                    @else
                                        <span class="badge bg-success">SKPI</span>
                                    @endif
                                </td>
                                <td><i class="bi bi-file-pdf text-danger me-2"></i>{{ $doc->filename }}</td>
                                <td data-order="{{ $doc->created_at->timestamp }}">{{ $doc->created_at->format('d M Y') }}
                                </td>
                                <td>
                                    @if ($doc->tx_id)
                                        <span class="badge bg-success">Terverifikasi</span>
                                    @else
                                        <span class="badge bg-warning">Menunggu</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">Belum ada dokumen yang diunggah.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Inisialisasi DataTables untuk tabel dokumen
        $(document).ready(function() {
            $('#documentsTable').DataTable({
                language: {
                    search: "Cari:",
                    lengthMenu: "Tampilkan _MENU_ data",
                    info: "Menampilkan _START_ - _END_ dari _TOTAL_ dokumen",
                    paginate: {
                        previous: "Sebelumnya",
                        next: "Berikutnya"
                    }
                },
                order: [
                    [4, 'desc']
                ] // Urutkan berdasarkan Tgl. Upload (kolom ke-4)
            });
        });
    </script>
@endpush
