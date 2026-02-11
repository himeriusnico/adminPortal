@extends('institutions.layout')

@section('institution-content')
    <div class="container-fluid">
        <!-- Header Section -->
        <div class="row mb-2">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h1 class="h3 mb-2">Pengaturan Institusi</h1>
                        <p class="text-muted">Kelola Fakultas dan Program Studi di institusi Anda</p>
                    </div>
                    {{-- <div class="d-flex">
                        <div class="me-2">
                            <span class="badge bg-primary p-2">
                                <i class="bi bi-building me-1"></i>
                                {{ $faculties->count() }} Fakultas
                            </span>
                        </div>
                        <div>
                            <span class="badge bg-success p-2">
                                <i class="bi bi-mortarboard me-1"></i>
                                {{ $programStudies->count() }} Program Studi
                            </span>
                        </div>
                    </div> --}}
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="row mb-1">
            <div class="col-md-6 mb-3">
                <div class="card stat-card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="stat-icon bg-primary bg-opacity-10 text-primary rounded-circle p-3 me-3">
                                <i class="bi bi-building fs-4"></i>
                            </div>
                            <div>
                                <h4 class="mb-0">{{ $faculties->count() }}</h4>
                                <p class="text-muted mb-0">Total Fakultas</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-3">
                <div class="card stat-card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="stat-icon bg-success bg-opacity-10 text-success rounded-circle p-3 me-3">
                                <i class="bi bi-mortarboard fs-4"></i>
                            </div>
                            <div>
                                <h4 class="mb-0">{{ $programStudies->count() }}</h4>
                                <p class="text-muted mb-0">Total Program Studi</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="row">
            <!-- Fakultas Section -->
            <div class="col-lg-6 mb-4">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center py-3">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-building me-2"></i>Daftar Fakultas
                        </h5>
                        <button class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#addFacultyModal">
                            <i class="bi bi-plus-lg me-1"></i>Tambah Fakultas
                        </button>
                    </div>
                    <div class="card-body p-0">
                        @if ($faculties->isEmpty())
                            <div class="text-center py-5">
                                <div class="mb-3">
                                    <i class="bi bi-building text-muted" style="font-size: 3rem;"></i>
                                </div>
                                <h5 class="text-muted">Belum ada fakultas</h5>
                                <p class="text-muted mb-3">Mulai dengan menambahkan fakultas pertama Anda</p>
                                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addFacultyModal">
                                    <i class="bi bi-plus-lg me-1"></i>Tambah Fakultas
                                </button>
                            </div>
                        @else
                            <div class="list-group list-group-flush">
                                @foreach ($faculties as $faculty)
                                    <div class="list-group-item d-flex justify-content-between align-items-center py-3">
                                        <div class="d-flex align-items-center">
                                            <div class="faculty-icon bg-primary bg-opacity-10 text-primary rounded p-2 me-3">
                                                <i class="bi bi-building"></i>
                                            </div>
                                            <div>
                                                <h6 class="mb-1">{{ $faculty->name }}</h6>
                                                <small class="text-muted">
                                                    {{ $faculty->program_studies_count ?? 0 }} Program Studi
                                                </small>
                                            </div>
                                        </div>
                                        <div class="btn-group">
                                            <button class="btn btn-sm btn-outline-primary"
                                                onclick="editFaculty({{ $faculty->id }}, '{{ $faculty->name }}')"
                                                title="Edit Fakultas">
                                                <i class="bi bi-pencil"></i>
                                            </button>
                                            <button class="btn btn-sm btn-outline-danger"
                                                onclick="confirmDeleteFaculty({{ $faculty->id }}, '{{ $faculty->name }}')"
                                                title="Hapus Fakultas">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Program Studi Section -->
            <div class="col-lg-6 mb-4">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-success text-white d-flex justify-content-between align-items-center py-3">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-mortarboard me-2"></i>Daftar Program Studi
                        </h5>
                        <button class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#addProgramStudyModal"
                            @if ($faculties->isEmpty()) disabled title="Tambahkan fakultas terlebih dahulu" @endif>
                            <i class="bi bi-plus-lg me-1"></i>Tambah Prodi
                        </button>
                    </div>
                    <div class="card-body p-0">
                        @if ($programStudies->isEmpty())
                            <div class="text-center py-5">
                                <div class="mb-3">
                                    <i class="bi bi-mortarboard text-muted" style="font-size: 3rem;"></i>
                                </div>
                                <h5 class="text-muted">Belum ada program studi</h5>
                                @if ($faculties->isNotEmpty())
                                    <p class="text-muted mb-3">Mulai dengan menambahkan program studi pertama Anda</p>
                                    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addProgramStudyModal">
                                        <i class="bi bi-plus-lg me-1"></i>Tambah Program Studi
                                    </button>
                                @else
                                    <p class="text-muted mb-3">Tambahkan fakultas terlebih dahulu untuk membuat program
                                        studi</p>
                                    <button class="btn btn-outline-secondary" disabled>
                                        <i class="bi bi-plus-lg me-1"></i>Tambah Program Studi
                                    </button>
                                @endif
                            </div>
                        @else
                            <div class="list-group list-group-flush">
                                @foreach ($programStudies as $program)
                                    <div class="list-group-item d-flex justify-content-between align-items-center py-3">
                                        <div class="d-flex align-items-center">
                                            <div class="program-icon bg-success bg-opacity-10 text-success rounded p-2 me-3">
                                                <i class="bi bi-mortarboard"></i>
                                            </div>
                                            <div>
                                                <h6 class="mb-1">{{ $program->name }}</h6>
                                                <small class="text-muted">
                                                    {{ $program->faculty->name ?? 'N/A' }}
                                                </small>
                                            </div>
                                        </div>
                                        <div class="btn-group">
                                            <button class="btn btn-sm btn-outline-success"
                                                onclick="editProgramStudy({{ $program->id }}, '{{ $program->name }}', {{ $program->faculty_id }})"
                                                title="Edit Program Studi">
                                                <i class="bi bi-pencil"></i>
                                            </button>
                                            <button class="btn btn-sm btn-outline-danger"
                                                onclick="confirmDeleteProgramStudy({{ $program->id }}, '{{ $program->name }}')"
                                                title="Hapus Program Studi">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-1 font-weight-bold">Keamanan Akun</h5>
                        <p class="text-muted mb-0">Perbarui kata sandi institusi Anda secara berkala untuk menjaga keamanan.
                        </p>
                    </div>
                    <button class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#changePasswordModal">
                        <i class="bi bi-shield-lock me-1"></i> Ganti Password
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm border-0 mt-4">
        <div class="card-header bg-dark text-white">
            <h5 class="mb-0"><i class="bi bi-shield-lock me-2"></i>Identitas Institusi</h5>
        </div>
        <div class="card-body">
            @php $inst = App\Models\Institution::find(Auth::user()->institution_id); @endphp

            @if(!$inst->public_key)
                <div class="alert alert-warning">
                    <p>Identitas Institutsi belum dibuat. Anda perlu membuat Keypair sebelum dapat mengelola dokumen akademik.
                    </p>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalGenerateKey">
                        <i class="bi bi-key-fill me-1"></i> Generate Keypair
                    </button>
                </div>
            @else
                <div class="mb-3">
                    <label class="form-label fw-bold">Public Key (Identitas Publik)</label>
                    <pre class="bg-light p-3 border rounded" style="font-size: 0.8rem;">{{ $inst->public_key }}</pre>
                </div>
            @endif

            @if($inst->public_key)
                <div class="mt-3">
                    <button class="btn btn-outline-dark btn-sm" data-bs-toggle="modal" data-bs-target="#modalViewKey">
                        <i class="bi bi-eye me-1"></i> Lihat Private Key
                    </button>
                </div>
            @endif

            <div class="modal fade" id="modalViewKey" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content">
                        <form id="formViewKey">
                            @csrf
                            <div class="modal-header bg-danger text-white">
                                <h5 class="modal-title"><i class="bi bi-exclamation-triangle me-2"></i>Private Key (JANGAN
                                    DIBAGIKAN KE SIAPAPUN)
                                </h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <div id="inputPassphraseWrapper">
                                    <p class="text-muted">Masukkan passphrase Anda</p>
                                    <div class="mb-3">
                                        <label class="form-label">Passphrase</label>
                                        <input type="password" name="passphrase" class="form-control" required>
                                    </div>
                                </div>

                                <div id="keyDisplayWrapper" style="display: none;">
                                    <label class="form-label fw-bold text-danger">Private Key Anda (Jangan
                                        Sebarkan!):</label>
                                    <textarea id="privateKeyArea" class="form-control bg-light font-monospace" rows="10"
                                        readonly style="font-size: 0.8rem;"></textarea>
                                    <div class="mt-2">
                                        <button type="button" class="btn btn-sm btn-secondary" onclick="copyKey()">
                                            <i class="bi bi-clipboard"></i> Salin ke Clipboard
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-danger" id="btnDecrypt">Dekripsi & Tampilkan</button>
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalGenerateKey" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form id="formGenKey">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Generate Identitas Institusi</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Buat Passphrase Keamanan</label>
                            <input type="password" name="passphrase" class="form-control" required minlength="8"
                                id="pass_input">
                            <div class="form-text">Passphrase ini digunakan untuk mengenkripsi Private Key Anda. Jangan
                                sampai lupa!</div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary" id="btnSubmitKey">Simpan & Generate</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Add Faculty Modal -->
    <div class="modal fade" id="addFacultyModal" tabindex="-1" aria-labelledby="addFacultyModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <form action="{{ route('faculties.store') }}" method="POST" id="addFacultyForm">
                    @csrf
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title" id="addFacultyModalLabel">
                            <i class="bi bi-building me-2"></i>Tambah Fakultas Baru
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label for="faculty_name" class="form-label fw-semibold">Nama Fakultas <span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-lg" id="faculty_name" name="name" required
                                placeholder="Contoh: Fakultas Teknik">
                            <div class="form-text">Masukkan nama fakultas yang lengkap dan jelas</div>
                        </div>
                    </div>
                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="bi bi-check-lg me-1"></i>Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Faculty Modal -->
    <div class="modal fade" id="editFacultyModal" tabindex="-1" aria-labelledby="editFacultyModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <form action="" method="POST" id="editFacultyForm">
                    @csrf
                    @method('PUT')
                    <div class="modal-header bg-warning text-dark">
                        <h5 class="modal-title" id="editFacultyModalLabel">
                            <i class="bi bi-pencil-square me-2"></i>Edit Fakultas
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label for="edit_faculty_name" class="form-label fw-semibold">Nama Fakultas <span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-lg" id="edit_faculty_name" name="name"
                                required placeholder="Contoh: Fakultas Teknik">
                            <div class="form-text">Perbarui nama fakultas sesuai kebutuhan</div>
                        </div>
                    </div>
                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-warning px-4 text-white">
                            <i class="bi bi-check-lg me-1"></i>Perbarui
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Add Program Study Modal -->
    <div class="modal fade" id="addProgramStudyModal" tabindex="-1" aria-labelledby="addProgramStudyModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <form action="{{ route('program-studies.store') }}" method="POST" id="addProgramStudyForm">
                    @csrf
                    <div class="modal-header bg-success text-white">
                        <h5 class="modal-title" id="addProgramStudyModalLabel">
                            <i class="bi bi-mortarboard me-2"></i>Tambah Program Studi Baru
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label for="faculty_id" class="form-label fw-semibold">Fakultas <span
                                    class="text-danger">*</span></label>
                            <select class="form-select form-select-lg" id="faculty_id" name="faculty_id" required>
                                <option value="">Pilih Fakultas</option>
                                @foreach ($faculties as $faculty)
                                    <option value="{{ $faculty->id }}">{{ $faculty->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="program_study_name" class="form-label fw-semibold">Nama Program Studi <span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-lg" id="program_study_name" name="name"
                                required placeholder="Contoh: Teknik Informatika">
                            <div class="form-text">Masukkan nama program studi yang lengkap dan jelas</div>
                        </div>
                    </div>
                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-success px-4">
                            <i class="bi bi-check-lg me-1"></i>Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Program Study Modal -->
    <div class="modal fade" id="editProgramStudyModal" tabindex="-1" aria-labelledby="editProgramStudyModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <form action="" method="POST" id="editProgramStudyForm">
                    @csrf
                    @method('PUT')
                    <div class="modal-header bg-info text-white">
                        <h5 class="modal-title" id="editProgramStudyModalLabel">
                            <i class="bi bi-pencil-square me-2"></i>Edit Program Studi
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label for="edit_faculty_id" class="form-label fw-semibold">Fakultas <span
                                    class="text-danger">*</span></label>
                            <select class="form-select form-select-lg" id="edit_faculty_id" name="faculty_id" required>
                                <option value="">Pilih Fakultas</option>
                                @foreach ($faculties as $faculty)
                                    <option value="{{ $faculty->id }}">{{ $faculty->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="edit_program_study_name" class="form-label fw-semibold">Nama Program Studi <span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-lg" id="edit_program_study_name" name="name"
                                required placeholder="Contoh: Teknik Informatika">
                            <div class="form-text">Perbarui nama program studi sesuai kebutuhan</div>
                        </div>
                    </div>
                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-info px-4 text-white">
                            <i class="bi bi-check-lg me-1"></i>Perbarui
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Modal ganti password --}}
    <div class="modal fade" id="changePasswordModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <form action="{{ route('institution.update-password') }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-header bg-dark text-white">
                        <h5 class="modal-title"><i class="bi bi-key me-2"></i>Ganti Password</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Password Saat Ini</label>
                            <div class="input-group">
                                <input type="password" name="current_password" id="current_password" class="form-control"
                                    required>
                                <button class="btn btn-outline-secondary toggle-password" type="button"
                                    data-target="current_password">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                        </div>
                        <hr>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Password Baru</label>
                            <div class="input-group">
                                <input type="password" name="new_password" id="new_password" class="form-control" required>
                                <button class="btn btn-outline-secondary toggle-password" type="button"
                                    data-target="new_password">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Konfirmasi Password Baru</label>
                            <div class="input-group">
                                <input type="password" name="new_password_confirmation" id="new_password_confirmation"
                                    class="form-control" required>
                                <button class="btn btn-outline-secondary toggle-password" type="button"
                                    data-target="new_password_confirmation">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-dark px-4">Update Password</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Hidden Forms for Delete Actions -->
    <form id="deleteFacultyForm" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>

    <form id="deleteProgramStudyForm" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>

    @push('styles')
        <style>
            .stat-card {
                transition: transform 0.2s;
                border-radius: 12px;
            }

            .stat-card:hover {
                transform: translateY(-5px);
            }

            .stat-icon {
                width: 60px;
                height: 60px;
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .faculty-icon,
            .program-icon {
                width: 45px;
                height: 45px;
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .card {
                border-radius: 12px;
                overflow: hidden;
            }

            .list-group-item {
                border-left: 0;
                border-right: 0;
                transition: background-color 0.2s;
            }

            .list-group-item:first-child {
                border-top: 0;
            }

            .list-group-item:last-child {
                border-bottom: 0;
            }

            .list-group-item:hover {
                background-color: rgba(0, 0, 0, 0.03);
            }

            .modal-content {
                border-radius: 16px;
            }

            .btn {
                border-radius: 8px;
            }

            .form-control,
            .form-select {
                border-radius: 8px;
            }

            .btn-group .btn {
                border-radius: 0;
            }

            .btn-group .btn:first-child {
                border-top-left-radius: 6px;
                border-bottom-left-radius: 6px;
            }

            .btn-group .btn:last-child {
                border-top-right-radius: 6px;
                border-bottom-right-radius: 6px;
            }
        </style>
    @endpush

    @push('scripts')
        <script>
            @if (session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: '{{ session('success') }}',
                    timer: 3000,
                    showConfirmButton: false,
                    toast: true,
                    position: 'top-end'
                });
            @endif

            @if (session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Terjadi Kesalahan',
                    text: '{{ session('error') }}',
                    timer: 5000,
                    confirmButtonText: 'Mengerti'
                });
            @endif

                // Faculty Functions
                function editFaculty(id, name) {
                    // Set form action
                    document.getElementById('editFacultyForm').action = `/faculties/${id}`;

                    // Set current name in input
                    document.getElementById('edit_faculty_name').value = name;

                    // Show modal
                    const editModal = new bootstrap.Modal(document.getElementById('editFacultyModal'));
                    editModal.show();

                    // Focus on input
                    setTimeout(() => {
                        document.getElementById('edit_faculty_name').focus();
                    }, 500);
                }

            function confirmDeleteFaculty(id, name) {
                Swal.fire({
                    title: 'Hapus Fakultas?',
                    html: `Anda akan menghapus fakultas <strong>"${name}"</strong>.<br><br>
                                                                                                                                                              <span class="text-danger">Perhatian: Semua program studi di bawah fakultas ini juga akan terhapus!</span>`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        const form = document.getElementById('deleteFacultyForm');
                        form.action = `/faculties/${id}`;
                        form.submit();
                    }
                });
            }

            // Program Study Functions
            function editProgramStudy(id, name, facultyId) {
                // Set form action
                document.getElementById('editProgramStudyForm').action = `/program-studies/${id}`;

                // Set current values in inputs
                document.getElementById('edit_program_study_name').value = name;
                document.getElementById('edit_faculty_id').value = facultyId;

                // Show modal
                const editModal = new bootstrap.Modal(document.getElementById('editProgramStudyModal'));
                editModal.show();

                // Focus on input
                setTimeout(() => {
                    document.getElementById('edit_program_study_name').focus();
                }, 500);
            }

            function confirmDeleteProgramStudy(id, name) {
                Swal.fire({
                    title: 'Hapus Program Studi?',
                    html: `Anda akan menghapus program studi <strong>"${name}"</strong>.`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        const form = document.getElementById('deleteProgramStudyForm');
                        form.action = `/program-studies/${id}`;
                        form.submit();
                    }
                });
            }

            // Auto-focus on first input when modals are shown
            document.addEventListener('DOMContentLoaded', function () {
                const facultyModal = document.getElementById('addFacultyModal');
                const programModal = document.getElementById('addProgramStudyModal');

                if (facultyModal) {
                    facultyModal.addEventListener('shown.bs.modal', function () {
                        document.getElementById('faculty_name').focus();
                    });
                }

                if (programModal) {
                    programModal.addEventListener('shown.bs.modal', function () {
                        document.getElementById('program_study_name').focus();
                    });
                }

                // Reset forms when modals are hidden
                const modals = ['addFacultyModal', 'editFacultyModal', 'addProgramStudyModal', 'editProgramStudyModal'];
                modals.forEach(modalId => {
                    const modal = document.getElementById(modalId);
                    if (modal) {
                        modal.addEventListener('hidden.bs.modal', function () {
                            const form = this.querySelector('form');
                            if (form && form.id.includes('add')) {
                                form.reset();
                            }
                        });
                    }
                });
            });

            // Form validation and submission feedback
            document.addEventListener('DOMContentLoaded', function () {
                const forms = document.querySelectorAll('form');
                forms.forEach(form => {
                    form.addEventListener('submit', function (e) {
                        const submitBtn = this.querySelector('button[type="submit"]');
                        if (submitBtn) {
                            submitBtn.disabled = true;
                            submitBtn.innerHTML =
                                '<i class="bi bi-hourglass-split me-1"></i>Memproses...';
                        }
                    });
                });
            });

            document.querySelectorAll('.toggle-password').forEach(button => {
                button.addEventListener('click', function () {
                    // Ambil ID target dari atribut data-target
                    const targetId = this.getAttribute('data-target');
                    const input = document.getElementById(targetId);
                    const icon = this.querySelector('i');

                    // Toggle tipe input
                    if (input.type === 'password') {
                        input.type = 'text';
                        icon.classList.remove('bi-eye');
                        icon.classList.add('bi-eye-slash'); // Ganti ikon jadi mata dicoret
                    } else {
                        input.type = 'password';
                        icon.classList.remove('bi-eye-slash');
                        icon.classList.add('bi-eye'); // Ganti kembali ke ikon mata normal
                    }
                });
            });

            $(document).ready(function () {
                $('#formGenKey').on('submit', function (e) {
                    e.preventDefault(); // MENCEGAH RELOAD HALAMAN

                    const formData = new FormData(this);
                    const submitBtn = $('#btnSubmitKey');

                    Swal.fire({
                        title: 'Konfirmasi Generate',
                        text: "Kunci kriptografi akan dibuat. Pastikan passphrase Anda aman.",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Ya, Generate!',
                        showLoaderOnConfirm: true,
                        preConfirm: () => {
                            return fetch("{{ route('institutions.generate-keys') }}", {
                                method: 'POST',
                                body: formData,
                                headers: { 'X-CSRF-TOKEN': $('input[name="_token"]').val(), 'Accept': 'application/json' }
                            })
                                .then(response => {
                                    if (!response.ok) return response.json().then(err => { throw new Error(err.message) });
                                    return response.json();
                                })
                                .catch(error => { Swal.showValidationMessage(`Gagal: ${error.message}`); });
                        }
                    }).then((result) => {
                        if (result.isConfirmed && result.value.success) {
                            Swal.fire('Berhasil!', result.value.message, 'success').then(() => {
                                // Penanganan Modal yang Aman
                                const modalEl = document.getElementById('modalGenerateKey');
                                const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
                                modal.hide();

                                location.reload();
                            });
                        }
                    });
                });
            });

            $(document).ready(function () {
                $('#formViewKey').on('submit', function (e) {
                    e.preventDefault();
                    const btn = $('#btnDecrypt');
                    const formData = new FormData(this);

                    btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span> Memproses...');

                    fetch("{{ route('institution.view-private-key') }}", {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': $('input[name="_token"]').val(),
                            'Accept': 'application/json'
                        }
                    })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                $('#inputPassphraseWrapper').hide();
                                $('#btnDecrypt').hide();
                                $('#keyDisplayWrapper').show();
                                $('#privateKeyArea').val(data.private_key);
                            } else {
                                Swal.fire('Gagal', data.message, 'error');
                            }
                        })
                        .catch(err => Swal.fire('Error', 'Terjadi kesalahan sistem', 'error'))
                        .finally(() => {
                            btn.prop('disabled', false).text('Dekripsi & Tampilkan');
                        });
                });

                // Reset modal saat ditutup
                $('#modalViewKey').on('hidden.bs.modal', function () {
                    $('#inputPassphraseWrapper').show();
                    $('#btnDecrypt').show();
                    $('#keyDisplayWrapper').hide();
                    $('#privateKeyArea').val('');
                    $('#formViewKey')[0].reset();
                });
            });

            function copyKey() {
                const copyText = document.getElementById("privateKeyArea");
                copyText.select();
                document.execCommand("copy");
                Swal.fire({ title: 'Tersalin!', icon: 'success', toast: true, position: 'top-end', timer: 2000, showConfirmButton: false });
            }
        </script>
    @endpush
@endsection