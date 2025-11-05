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
                                            <div
                                                class="faculty-icon bg-primary bg-opacity-10 text-primary rounded p-2 me-3">
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
                                    <button class="btn btn-success" data-bs-toggle="modal"
                                        data-bs-target="#addProgramStudyModal">
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
                                            <div
                                                class="program-icon bg-success bg-opacity-10 text-success rounded p-2 me-3">
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

    <!-- Add Faculty Modal -->
    <div class="modal fade" id="addFacultyModal" tabindex="-1" aria-labelledby="addFacultyModalLabel"
        aria-hidden="true">
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
                            <input type="text" class="form-control form-control-lg" id="faculty_name" name="name"
                                required placeholder="Contoh: Fakultas Teknik">
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
    <div class="modal fade" id="editFacultyModal" tabindex="-1" aria-labelledby="editFacultyModalLabel"
        aria-hidden="true">
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
                            <input type="text" class="form-control form-control-lg" id="edit_faculty_name"
                                name="name" required placeholder="Contoh: Fakultas Teknik">
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
                            <input type="text" class="form-control form-control-lg" id="program_study_name"
                                name="name" required placeholder="Contoh: Teknik Informatika">
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
                            <input type="text" class="form-control form-control-lg" id="edit_program_study_name"
                                name="name" required placeholder="Contoh: Teknik Informatika">
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
            // Show success/error messages with SweetAlert
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
            document.addEventListener('DOMContentLoaded', function() {
                const facultyModal = document.getElementById('addFacultyModal');
                const programModal = document.getElementById('addProgramStudyModal');

                if (facultyModal) {
                    facultyModal.addEventListener('shown.bs.modal', function() {
                        document.getElementById('faculty_name').focus();
                    });
                }

                if (programModal) {
                    programModal.addEventListener('shown.bs.modal', function() {
                        document.getElementById('program_study_name').focus();
                    });
                }

                // Reset forms when modals are hidden
                const modals = ['addFacultyModal', 'editFacultyModal', 'addProgramStudyModal', 'editProgramStudyModal'];
                modals.forEach(modalId => {
                    const modal = document.getElementById(modalId);
                    if (modal) {
                        modal.addEventListener('hidden.bs.modal', function() {
                            const form = this.querySelector('form');
                            if (form && form.id.includes('add')) {
                                form.reset();
                            }
                        });
                    }
                });
            });

            // Form validation and submission feedback
            document.addEventListener('DOMContentLoaded', function() {
                const forms = document.querySelectorAll('form');
                forms.forEach(form => {
                    form.addEventListener('submit', function(e) {
                        const submitBtn = this.querySelector('button[type="submit"]');
                        if (submitBtn) {
                            submitBtn.disabled = true;
                            submitBtn.innerHTML =
                                '<i class="bi bi-hourglass-split me-1"></i>Memproses...';
                        }
                    });
                });
            });
        </script>
    @endpush
@endsection
