@extends('students.layout')

@section('student-content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-2">Manajemen Mahasiswa</h1>
            <p class="text-muted">Kelola data mahasiswa dan informasi akademik</p>
        </div>
        <div>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createStudentModal">
                <i class="bi bi-person-plus me-2"></i>Tambah Mahasiswa
            </button>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card stats-card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5 class="card-title">Total Mahasiswa</h5>
                            <h2 class="mb-0">{{ $students->total() }}</h2>
                        </div>
                        <div class="align-self-center">
                            <i class="bi bi-people display-6"></i>
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
                            <h5 class="card-title">Aktif</h5>
                            <h2 class="mb-0">{{ $activeStudents }}</h2>
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
                            <h5 class="card-title">Lulus</h5>
                            <h2 class="mb-0">{{ $graduatedStudents }}</h2>
                        </div>
                        <div class="align-self-center">
                            <i class="bi bi-mortarboard display-6"></i>
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
                            <h5 class="card-title">Non-Aktif</h5>
                            <h2 class="mb-0">{{ $inactiveStudents }}</h2>
                        </div>
                        <div class="align-self-center">
                            <i class="bi bi-pause-circle display-6"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Table -->
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">
                <i class="bi bi-list-ul me-2"></i>Daftar Mahasiswa
            </h5>
            <div class="d-flex">
                <input type="text" id="searchInput" class="form-control form-control-sm me-2"
                    placeholder="Cari mahasiswa..." style="width: 250px;">
                <button class="btn btn-sm btn-outline-secondary" onclick="exportData()">
                    <i class="bi bi-download me-1"></i>Export
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="studentsTable" class="table table-hover table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Mahasiswa</th>
                            <th>NIM</th>
                            <th>Program Studi</th>
                            <th>Fakultas</th>
                            <th>Institusi</th>
                            <th>Tahun Masuk</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($students as $student)
                            <tr>
                                <td>{{ $loop->iteration + ($students->currentPage() - 1) * $students->perPage() }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div
                                            class="student-photo bg-secondary d-flex align-items-center justify-content-center text-white me-3">
                                            <i class="bi bi-person-fill"></i>
                                        </div>
                                        <div>
                                            <strong>{{ $student->user->name }}</strong>
                                            <br>
                                            <small class="text-muted">{{ $student->user->email }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <code>{{ $student->student_id }}</code>
                                </td>
                                <td>{{ $student->program_study }}</td>
                                <td>{{ $student->faculty }}</td>
                                <td>
                                    <span class="badge bg-light text-dark">
                                        {{ $student->institution->name ?? 'N/A' }}
                                    </span>
                                </td>
                                <td>{{ $student->entry_year }}</td>
                                <td>
                                    @if ($student->status === 'active')
                                        <span class="badge bg-success status-badge">Aktif</span>
                                    @elseif($student->status === 'graduated')
                                        <span class="badge bg-primary status-badge">Lulus</span>
                                    @else
                                        <span class="badge bg-warning status-badge">Non-Aktif</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="{{ route('students.show', $student->id) }}" class="btn btn-sm btn-info"
                                            title="Detail">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <button class="btn btn-sm btn-warning" title="Edit"
                                            onclick="editStudent({{ $student->id }})">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <button class="btn btn-sm btn-danger" title="Hapus"
                                            onclick="deleteStudent({{ $student->id }})">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-4">
                                    <i class="bi bi-people display-1 text-muted d-block mb-3"></i>
                                    <h5 class="text-muted">Belum ada data mahasiswa</h5>
                                    <p class="text-muted">Mulai dengan menambahkan mahasiswa baru</p>
                                    <button class="btn btn-primary" data-bs-toggle="modal"
                                        data-bs-target="#createStudentModal">
                                        <i class="bi bi-person-plus me-2"></i>Tambah Mahasiswa Pertama
                                    </button>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if ($students->hasPages())
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div class="text-muted">
                        Menampilkan {{ $students->firstItem() }} - {{ $students->lastItem() }} dari
                        {{ $students->total() }} mahasiswa
                    </div>
                    <nav>
                        {{ $students->links() }}
                    </nav>
                </div>
            @endif
        </div>
    </div>

    <!-- Create Student Modal -->
    <div class="modal fade" id="createStudentModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Mahasiswa Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="createStudentForm">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">Nama Lengkap *</label>
                                <input type="text" class="form-control" id="name" name="name" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Email *</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="student_id" class="form-label">NIM *</label>
                                <input type="text" class="form-control" id="student_id" name="student_id" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="program_study" class="form-label">Program Studi *</label>
                                <input type="text" class="form-control" id="program_study" name="program_study"
                                    required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="faculty" class="form-label">Fakultas *</label>
                                <input type="text" class="form-control" id="faculty" name="faculty" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="entry_year" class="form-label">Tahun Masuk *</label>
                                <input type="number" class="form-control" id="entry_year" name="entry_year"
                                    min="2000" max="2030" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="phone" class="form-label">Nomor Telepon</label>
                            <input type="tel" class="form-control" id="phone" name="phone">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary" onclick="submitStudentForm()">Simpan</button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            // Simple search functionality
            document.getElementById('searchInput').addEventListener('input', function(e) {
                const searchTerm = e.target.value.toLowerCase();
                const rows = document.querySelectorAll('#studentsTable tbody tr');

                rows.forEach(row => {
                    const text = row.textContent.toLowerCase();
                    row.style.display = text.includes(searchTerm) ? '' : 'none';
                });
            });

            function exportData() {
                // Simple export functionality - can be enhanced with proper CSV/Excel export
                alert('Fitur export akan diimplementasikan kemudian');
            }

            function editStudent(id) {
                // Redirect to edit page or open edit modal
                window.location.href = `/students/${id}/edit`;
            }

            function deleteStudent(id) {
                if (confirm('Apakah Anda yakin ingin menghapus mahasiswa ini?')) {
                    // Implement delete functionality
                    fetch(`/students/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json'
                        }
                    }).then(response => {
                        if (response.ok) {
                            location.reload();
                        } else {
                            alert('Gagal menghapus mahasiswa');
                        }
                    });
                }
            }

            function submitStudentForm() {
                // Implement form submission
                alert('Fitur tambah mahasiswa akan diimplementasikan kemudian');
            }
        </script>
    @endpush
@endsection
