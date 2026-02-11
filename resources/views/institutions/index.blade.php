@extends('institutions.layout')

@section('institution-content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-2">Manajemen Institusi</h1>
            <p class="text-muted">Kelola data institusi pendidikan</p>
        </div>
        <div>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addInstitutionModal">
                <i class="bi bi-building-add me-2"></i>Tambah Institusi
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
                            <h5 class="card-title">Total Institusi</h5>
                            <h2 class="mb-0">{{ $institutions->count() }}</h2>
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
                            <h5 class="card-title">Aktif</h5>
                            <h2 class="mb-0">{{ $institutions->count() }}</h2>
                        </div>
                        <div class="align-self-center">
                            <i class="bi bi-check-circle display-6"></i>
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
                            <h5 class="card-title">Pegawai</h5>
                            <h2 class="mb-0">0</h2>
                        </div>
                        <div class="align-self-center">
                            <i class="bi bi-people display-6"></i>
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
                            <h5 class="card-title">Mahasiswa</h5>
                            <h2 class="mb-0">0</h2>
                        </div>
                        <div class="align-self-center">
                            <i class="bi bi-person display-6"></i>
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
                <i class="bi bi-list-ul me-2"></i>Daftar Institusi
            </h5>
            {{-- <div class="d-flex">
                <input type="text" id="searchInput" class="form-control form-control-sm me-2"
                    placeholder="Cari institusi..." style="width: 250px;">
                <button class="btn btn-sm btn-outline-secondary" onclick="exportData()">
                    <i class="bi bi-download me-1"></i>Export
                </button>
            </div> --}}
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="institutionsTable" class="table table-hover table-striped" style="width:100%">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Institusi</th>
                            <th>Email</th>
                            <th>Alamat</th>
                            <th>Tanggal Dibuat</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($institutions as $institution)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="institution-logo me-3">
                                            {{ substr($institution->name, 0, 2) }}
                                        </div>
                                        <div><strong>{{ $institution->name }}</strong></div>
                                    </div>
                                </td>
                                <td>
                                    <a href="mailto:{{ $institution->email }}" class="text-decoration-none">
                                        {{ $institution->email }}
                                    </a>
                                </td>
                                <td>
                                    @if ($institution->alamat)
                                        <span title="{{ $institution->alamat }}">
                                            {{ Str::limit($institution->alamat, 30) }}
                                        </span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>{{ $institution->created_at->format('d M Y') }}</td>
                                <td>
                                    <div class="action-buttons">
                                        <button class="btn btn-sm btn-info" onclick="showDetail({{ $institution->id }})"><i
                                                class="bi bi-eye"></i></button>
                                        <button class="btn btn-sm btn-warning"
                                            onclick="editInstitution({{ $institution->id }})"><i
                                                class="bi bi-pencil"></i></button>
                                        <button class="btn btn-sm btn-danger"
                                            onclick="deleteInstitution({{ $institution->id }})"><i
                                                class="bi bi-trash"></i></button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            {{-- @if ($institutions->hasPages())
            <div class="d-flex justify-content-between align-items-center mt-3">
                <div class="text-muted">
                    Menampilkan {{ $institutions->firstItem() }} - {{ $institutions->lastItem() }} dari
                    {{ $institutions->total() }} institusi
                </div>
                <nav>
                    {{ $institutions->links() }}
                </nav>
            </div>
            @endif --}}
        </div>
    </div>

    <!-- Create Institution Modal -->
    <div class="modal fade" id="addInstitutionModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="bi bi-building-add me-2"></i>Tambah Institusi Baru
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addInstitutionForm">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">Nama Institusi *</label>
                                <input type="text" class="form-control" id="name" name="name" required
                                    placeholder="Masukkan nama institusi">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Email *</label>
                                <input type="email" class="form-control" id="email" name="email" required
                                    placeholder="Masukkan email institusi">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="alamat" class="form-label">Alamat</label>
                            <textarea class="form-control" id="alamat" name="alamat" rows="3"
                                placeholder="Masukkan alamat lengkap institusi"></textarea>
                        </div>
                        {{-- <div class="mb-3">
                            <label for="passphrase" class="form-label fw-bold">Passphrase Admin *</label>
                            <div class="input-group">
                                <input type="password" class="form-control" id="passphrase" name="passphrase" required
                                    placeholder="Masukkan passphrase untuk enkripsi private key">
                                <button class="btn btn-outline-secondary toggle-passphrase" type="button"
                                    data-target="passphrase">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                            <small class="text-muted">Passphrase ini hanya diketahui admin. Gunakan kombinasi kuat dan
                                jangan lupa untuk dicatat!</small>
                        </div> --}}


                        <!-- Certificate Section (Read-only untuk sekarang) -->
                        {{-- <div class="alert alert-info">
                            <div class="col-md-6">
                                <label class="form-label">Public Key Milik Institusi</label>
                                <div class="form-control bg-light"
                                    style="min-height: 80px; font-family: monospace; font-size: 0.8rem;">
                                    [Public key akan digenerate otomatis]
                                </div>
                            </div>
                            <h6 class="alert-heading">
                                <i class="bi bi-key me-2"></i>Informasi Sertifikat
                            </h6>
                            <p class="mb-2">Sertifikat akan digenerate otomatis setelah institusi dibuat.</p>
                            <div class="row mt-3">
                                <div class="col-md-6">
                                    <label class="form-label">CA Certificate</label>
                                    <div class="form-control bg-light"
                                        style="min-height: 80px; font-family: monospace; font-size: 0.8rem;">
                                        [CA certificate akan digenerate otomatis]
                                    </div>
                                </div>
                            </div>
                        </div> --}}
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    {{-- <button type="button" class="btn btn-success" onclick="generateCertificate()">
                        <i class="bi bi-gear me-2"></i>Generate & Simpan
                    </button> --}}

                    <button type="button" class="btn btn-primary" id="btnGenerateCertificate">
                        <i class="bi bi-save me-2"></i>Daftarkan Institusi
                    </button>

                    {{-- <button type="button" class="btn btn-primary" onclick="submitInstitutionForm()">
                        <i class="bi bi-save me-2"></i>Simpan
                    </button> --}}
                </div>
            </div>
        </div>
    </div>

    <!-- Detail Institution Modal -->
    <div class="modal fade" id="detailInstitutionModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="bi bi-building me-2"></i>Detail Institusi
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <table class="table table-borderless">
                        <tbody>
                            <tr>
                                <th>Nama Institusi</th>
                                <td id="detailName"></td>
                            </tr>
                            <tr>
                                <th>Email</th>
                                <td id="detailEmail"></td>
                            </tr>
                            <tr>
                                <th>Alamat</th>
                                <td id="detailAlamat"></td>
                            </tr>
                            <tr>
                                <th>Public Key</th>
                                <td>
                                    <pre id="detailPublicKey"
                                        style="white-space: pre-wrap; word-break: break-word; max-height:300px; overflow-y:auto;"></pre>
                                </td>
                            </tr>
                            {{-- <tr>
                                <th>CA Certificate</th>
                                <td>
                                    <pre id="detailCACert"
                                        style="white-space: pre-wrap; word-break: break-word; max-height:300px; overflow-y:auto;"></pre>
                                </td>
                            </tr> --}}

                            <tr>
                                <th>Tanggal Dibuat</th>
                                <td id="detailCreatedAt"></td>
                            </tr>
                            <tr>
                                <th>Tanggal Diperbarui</th>
                                <td id="detailUpdatedAt"></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>


    @push('scripts')
        <script>
            $(document).ready(function () {
                // Logika Toggle Password/Passphrase (Global)
                $(document).on('click', '.toggle-passphrase, .toggle-password', function () {
                    const targetId = $(this).data('target');
                    const input = $('#' + targetId);
                    const icon = $(this).find('i');

                    if (input.attr('type') === 'password') {
                        input.attr('type', 'text');
                        icon.removeClass('bi-eye').addClass('bi-eye-slash');
                    } else {
                        input.attr('type', 'password');
                        icon.removeClass('bi-eye-slash').addClass('bi-eye');
                    }
                });

                // Opsional: Reset type input saat modal ditutup
                $('#addInstitutionModal').on('hidden.bs.modal', function () {
                    const passInput = $('#passphrase');
                    passInput.attr('type', 'password');
                    $(this).find('.toggle-passphrase i').removeClass('bi-eye-slash').addClass('bi-eye');
                });
            });
            // $(document).ready(function() {
            //     // Inisialisasi DataTables
            //     $('#institutionsTable').DataTable({
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
            //                 next: ">",
            //                 previous: "<"
            //             }
            //         },
            //         responsive: true,
            //         order: [
            //             [4, 'desc']
            //         ], // Default urut berdasarkan tanggal dibuat
            //         pageLength: 10,
            //         lengthMenu: [
            //             [5, 10, 25, 50, -1],
            //             [5, 10, 25, 50, "All"]
            //         ],
            //         columnDefs: [{
            //                 targets: [5], // Kolom aksi
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
                initDataTable('#institutionsTable', {
                    language: {
                        zeroRecords: "Belum ada data institusi. Silakan tambah institusi baru."
                    },
                    order: [
                        [4]
                    ], // Default urut berdasarkan tanggal dibuat
                    columnDefs: [{
                        targets: [5], // Kolom aksi
                        orderable: false,
                        searchable: false
                    }]
                });
            })

            // Simple search functionality
            // document.getElementById('searchInput').addEventListener('input', function(e) {
            //     const searchTerm = e.target.value.toLowerCase();
            //     const rows = document.querySelectorAll('#institutionsTable tbody tr');

            //     rows.forEach(row => {
            //         const text = row.textContent.toLowerCase();
            //         row.style.display = text.includes(searchTerm) ? '' : 'none';
            //     });
            // });

            document.getElementById('btnGenerateCertificate').addEventListener('click', function () {
                const form = document.getElementById('addInstitutionForm');
                const formData = new FormData(form);

                if (!form.checkValidity()) {
                    form.reportValidity();
                    return;
                }

                // Pastikan passphrase minimal misal 8 karakter
                // const passphrase = formData.get('passphrase');
                // console.log('Passphrase:', passphrase);

                // if (!passphrase || passphrase.length < 8) {
                //     Swal.fire({
                //         title: "Passphrase terlalu pendek!",
                //         text: "Gunakan passphrase minimal 8 karakter.",
                //         icon: "warning",
                //         confirmButtonColor: "#dc3545"
                //     });
                //     return;
                // }

                Swal.fire({
                    title: "Daftarkan Institusi ?",
                    text: "Sistem akan mendaftarkan institusi baru dan membuatkan akun admin.",
                    icon: "question",
                    showCancelButton: true,
                    confirmButtonColor: "#198754",
                    cancelButtonColor: "#6c757d",
                    confirmButtonText: "Ya, Lanjutkan!",
                    cancelButtonText: "Batal",
                    showLoaderOnConfirm: true,
                    preConfirm: () => {
                        return fetch("{{ route('institutions.store') }}", {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': form.querySelector('input[name=_token]').value,
                                'Accept': 'application/json'
                            },
                            body: formData
                        })
                            .then(async response => {
                                if (response.status === 422) {
                                    const data = await response.json();
                                    // Tampilkan pesan error validasi
                                    Swal.showValidationMessage(
                                        data.message + ': ' + Object.values(data.errors).flat().join(', ')
                                    );
                                    throw new Error('Pendaftaran gagal: Nama atau email institusi telah digunakan. Mohon periksa kembali data Anda.');
                                }
                                if (!response.ok) {
                                    throw new Error('Network response was not ok');
                                }
                                return response.json();
                            })
                            .catch(error => {
                                Swal.showValidationMessage(`Request failed: ${error}`);
                            });
                    },
                    allowOutsideClick: () => !Swal.isLoading()
                }).then((result) => {
                    if (result.isConfirmed) {
                        const data = result.value;
                        if (data.success) {
                            Swal.fire({
                                title: "Berhasil!",
                                html: `
                                                                    <p><strong>${formData.get('name')}</strong> berhasil ditambahkan!</p>
                                                                    <p class="text-muted mb-0">✓ Data institusi telah disimpan</p>
                                                                    <p class="text-muted mb-0">✓ Akun admin berhasil dibuat</p>
                                                                    <small class="text-danger mt-2 d-block">Admin harus login untuk generate Blockchain Keypair.</small>
                                                                `,
                                icon: "success",
                                confirmButtonColor: "#198754",
                            }).then(() => {
                                const modal = bootstrap.Modal.getInstance(document.getElementById(
                                    'addInstitutionModal'));
                                modal.hide();
                                form.reset();
                                location.reload();
                            });
                        } else {
                            Swal.fire({
                                title: "Gagal!",
                                text: data.message || "Terjadi kesalahan saat menyimpan institusi",
                                icon: "error",
                                confirmButtonColor: "#dc3545"
                            });
                        }
                    }
                }).catch(error => {
                    console.error('❌ Error:', error);
                    Swal.fire({
                        title: "Error!",
                        text: "Terjadi kesalahan jaringan. Silakan coba lagi.",
                        icon: "error",
                        confirmButtonColor: "#dc3545"
                    });
                });
            });



            function exportData() {
                alert('Fitur export akan diimplementasikan kemudian');
            }

            function showDetail(id) {
                // alert('Detail institusi ID: ' + id + ' akan ditampilkan');
                fetch(`/institutions/${id}`)
                    .then(response => response.json())
                    .then(res => {
                        if (res.success) {
                            const data = res.data;
                            document.getElementById('detailName').textContent = data.name;
                            document.getElementById('detailEmail').textContent = data.email;
                            document.getElementById('detailAlamat').textContent = data.alamat || '-';
                            document.getElementById('detailPublicKey').textContent = data.public_key || '-';
                            // document.getElementById('detailCACert').textContent = data.ca_cert || '-';
                            document.getElementById('detailCreatedAt').textContent = new Date(data.created_at)
                                .toLocaleString();
                            document.getElementById('detailUpdatedAt').textContent = new Date(data.updated_at)
                                .toLocaleString();

                            const modal = new bootstrap.Modal(document.getElementById('detailInstitutionModal'));
                            modal.show();
                        } else {
                            alert('Gagal menampilkan detail institusi.');
                        }
                    })
                    .catch(err => {
                        console.error('Error fetch detail:', err);
                        alert('Terjadi kesalahan saat mengambil data.');
                    });
            }

            function editInstitution(id) {
                alert('Edit institusi ID: ' + id + ' akan dibuka');
            }

            function deleteInstitution(id) {
                if (confirm('Apakah Anda yakin ingin menghapus institusi ini?')) {
                    alert('Institusi ID: ' + id + ' akan dihapus (simulasi)');
                }
            }

            function generateCertificate() {
                alert('Fungsi generate certificate akan diimplementasikan kemudian');
            }

            // function submitInstitutionForm() {
            //     const form = document.getElementById('addInstitutionForm');
            //     const formData = new FormData(form);

            //     // Simulasi submit
            //     alert('Data institusi akan disimpan:\n' +
            //         'Nama: ' + formData.get('name') + '\n' +
            //         'Email: ' + formData.get('email') + '\n' +
            //         'Alamat: ' + formData.get('alamat'));

            //     // Tutup modal setelah submit
            //     const modal = bootstrap.Modal.getInstance(document.getElementById('addInstitutionModal'));
            //     modal.hide();

            //     // Reset form
            //     form.reset();
            // }
        </script>
    @endpush
@endsection