@extends('layouts.app')

@section('title', 'Unggah Dokumen - Repositori Dokumen Akademik')

@section('content')
    {{-- @dd(Auth::user()); --}}
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

    {{-- Container untuk AJAX error alerts --}}
    <div id="ajaxAlertContainer"></div>

    <div class="card mb-4">
        <div class="card-header">
            <h5 class="card-title mb-0"><i class="bi bi-upload me-2"></i>Unggah Dokumen Baru</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('documents.store') }}" method="POST" enctype="multipart/form-data"
                id="documentUploadForm">
                @csrf
                <div class="row">
                    <div class="col-md-5 mb-3">
                        <label for="student_id" class="form-label">Mahasiswa *</label>
                        <select class="form-select @error('student_id') is-invalid @enderror" id="student_id"
                            name="student_id" required>
                            <option value="" disabled selected>-- Pilih Mahasiswa --</option>
                            @forelse ($students as $student)
                                <option value="{{ $student->id }}" {{ old('student_id') == $student->id ? 'selected' : '' }}>
                                    {{ $student->user->name ?? 'User Hilang' }} (NIM: {{ $student->student_id }})
                                </option>
                            @empty
                                <option value="" disabled>Belum ada data mahasiswa di institusi Anda</option>
                            @endforelse
                        </select>
                    </div>

                    <div class="col-md-3 mb-3">
                        <label for="document_type" class="form-label">Jenis Dokumen *</label>
                        <select class="form-select @error('document_type_id') is-invalid @enderror" id="document_type_id"
                            name="document_type_id" required>
                            <option value="" disabled selected>-- Pilih Jenis --</option>
                            @foreach ($documentTypes as $type)
                                <option value="{{ $type->id }}" {{ old('document_type_id') == $type->id ? 'selected' : '' }}>
                                    {{ $type->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="file" class="form-label">File PDF (Maks 2MB) *</label>
                        <input class="form-control @error('file') is-invalid @enderror" type="file" id="file" name="file"
                            accept=".pdf" required>
                    </div>
                </div>

                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#passphraseModal">
                    <i class="bi bi-upload me-2"></i>Unggah
                </button>

                <input type="hidden" name="passphrase" id="hiddenPassphrase" value="">

                <input type="hidden" name="force_replace" id="force_replace" value="false">
            </form>
        </div>
    </div>

    <div class="modal fade" id="passphraseModal" tabindex="-1" aria-labelledby="passphraseModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="passphraseModalLabel">
                        <i class="bi bi-shield-check me-2"></i>Konfirmasi Unggah & Tanda Tangan Digital
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <p>Masukkan **Passphrase Kunci Privat Institusi** Anda untuk mendekripsi kunci dan menandatangani
                        dokumen ini secara digital.</p>

                    <div class="mb-3">
                        <label for="passphrase_input" class="form-label fw-bold">Passphrase *</label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="passphrase_input" required autocomplete="off"
                                placeholder="Masukkan passphrase kunci privat">
                            <button class="btn btn-outline-secondary toggle-passphrase" type="button"
                                data-target="passphrase_input">
                                <i class="bi bi-eye"></i>
                            </button>
                        </div>
                    </div>

                    <div id="passphrase-alert" class="alert alert-danger d-none" role="alert">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i> Passphrase tidak boleh kosong.
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary px-4" id="confirmUploadBtn">
                        <i class="bi bi-lock-fill me-2"></i>Verifikasi & Unggah
                    </button>
                </div>
            </div>
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
                            <th>Hapus</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($documents as $doc)
                            <tr>
                                <td>{{ $doc->student->user->name ?? 'N/A' }}</td>
                                <td><code>{{ $doc->student->student_id ?? 'N/A' }}</code></td>
                                <td>
                                    <span class="badge bg-secondary">{{ $doc->documentType->name ?? 'Jenis Hilang' }}</span>
                                </td>
                                <td>
                                    <a href="{{ route('documents.view', $doc->id) }}" target="_blank"
                                        style="text-decoration:none">
                                        <i class="bi bi-file-pdf text-danger me-2"></i>{{ $doc->filename }}
                                    </a>
                                </td>
                                <td data-order="{{ $doc->created_at->timestamp }}">
                                    {{ $doc->created_at->format('d M Y') }}
                                </td>
                                <td>
                                    @if ($doc->tx_id == null)
                                        <div class="d-flex flex-column gap-1">
                                            <span class="badge bg-warning">Menunggu Verifikasi</span>

                                            <button class="btn btn-sm btn-outline-primary send-blockchain-btn"
                                                data-doc-id="{{ $doc->id }}" title="Kirim ke jaringan blockchain">
                                                <i class="bi bi-shield-check me-1"></i> Verifikasi
                                            </button>
                                        </div>
                                    @else
                                        <div class="d-flex flex-column gap-1">
                                            <span class="badge bg-success">Terverifikasi Dalam Blockchain</span>

                                            <button class="btn btn-sm btn-info view-blockchain-btn" data-doc-id="{{ $doc->id }}">
                                                <i class="bi bi-eye me-1"></i> View on Blockchain
                                            </button>
                                        </div>
                                    @endif
                                </td>

                                <td>
                                    @if (is_null($doc->tx_id))
                                        <form action="{{ route('documents.destroy', $doc->id) }}" method="POST"
                                            class="doc-delete-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus dokumen">
                                                <i class="bi bi-trash me-1"></i> Hapus
                                            </button>
                                        </form>
                                    @else
                                        <button class="btn btn-sm btn-outline-secondary"
                                            title="Sudah diverifikasi; tidak dapat dihapus" disabled>
                                            <i class="bi bi-trash me-1"></i> Hapus
                                        </button>
                                    @endif
                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">Belum ada dokumen yang diunggah.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- modal blockchain view verification --}}
    <div class="modal fade" id="blockchainModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Blockchain Data</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <pre id="blockchainModalBody" style="background:#f5f5f5;padding:15px;border-radius:5px;"></pre>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Fungsi umum untuk toggle password/passphrase
            const toggleButtons = document.querySelectorAll('.toggle-passphrase, .toggle-password');

            toggleButtons.forEach(button => {
                button.addEventListener('click', function () {
                    const targetId = this.getAttribute('data-target');
                    const input = document.getElementById(targetId);
                    const icon = this.querySelector('i');

                    if (input.type === 'password') {
                        input.type = 'text';
                        icon.classList.replace('bi-eye', 'bi-eye-slash');
                    } else {
                        input.type = 'password';
                        icon.classList.replace('bi-eye-slash', 'bi-eye');
                    }
                });
            });

            // Reset input saat modal ditutup (opsional demi keamanan)
            const passphraseModal = document.getElementById('passphraseModal');
            if (passphraseModal) {
                passphraseModal.addEventListener('hidden.bs.modal', function () {
                    const input = document.getElementById('passphrase_input');
                    input.type = 'password'; // Kembalikan ke tipe password
                    input.value = ''; // Kosongkan input demi keamanan
                    const icon = this.querySelector('.toggle-passphrase i');
                    icon.classList.replace('bi-eye-slash', 'bi-eye');
                    document.getElementById('passphrase-alert').classList.add('d-none');
                });
            }
        });
        $(document).ready(function () {
            @if ($documents->count() > 0)
                $('#documentsTable').DataTable({
                    language: {
                        search: "Cari:",
                        lengthMenu: "Tampilkan _MENU_ data",
                        info: "Menampilkan _START_ - _END_ dari _TOTAL_ dokumen",
                        paginate: {
                            previous: "Sebelumnya",
                            next: "Berikutnya"
                        },
                        emptyTable: "Belum ada dokumen yang diunggah"
                    },
                    order: [
                        [4, 'desc']
                    ]
                });
            @endif

            // ✅ SUBMIT VIA AJAX - Handle JSON Response
            $('#confirmUploadBtn').on('click', function () {
                const inputPassphrase = $('#passphrase_input').val();
                const storedPassphrase = $('#hiddenPassphrase').val();
                const passphrase = inputPassphrase && inputPassphrase.length > 0 ? inputPassphrase : storedPassphrase;
                const form = $('#documentUploadForm');

                console.log('Passphrase modal value (input):', inputPassphrase);
                console.log('Passphrase fallback (hidden):', storedPassphrase);
                console.log('Using passphrase length:', (passphrase || '').length);

                // Passphrase must be at least 8 chars (mirror backend)
                if (!passphrase || passphrase.length < 8) {
                    $('#passphrase-alert')
                        .removeClass('d-none')
                        .text('Passphrase wajib diisi (min. 8 karakter).');
                    console.log('Validation failed: Passphrase too short or empty.');
                    return;
                }

                // Client-side file validation to avoid uploading large/invalid files
                const fileInput = document.getElementById('file');
                const file = fileInput?.files?.[0];
                if (!file) {
                    showErrorAlert('Silakan pilih file PDF untuk diunggah.');
                    return;
                }
                const isPdf = file.type === 'application/pdf' || /\.pdf$/i.test(file.name);
                if (!isPdf) {
                    showErrorAlert('File harus dalam bentuk PDF.');
                    return;
                }
                const maxSize = 2 * 1024 * 1024; // 2MB
                if (file.size > maxSize) {
                    showErrorAlert('Ukuran file maksimal 2MB.');
                    return;
                }

                // Persist passphrase in hidden field so re-submits (e.g., after 409 duplicate) still work
                $('#hiddenPassphrase').val(passphrase);
                console.log('Hidden Passphrase value set to:', $('#hiddenPassphrase').val());
                $('#passphraseModal').modal('hide');

                const $btn = $(this);
                $btn.prop('disabled', true).html(
                    '<i class="bi bi-hourglass-split me-1"></i>Memproses...');

                // ✅ SUBMIT VIA AJAX
                const formData = new FormData(form[0]);

                $.ajax({
                    url: form.attr('action'),
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    dataType: 'json', // ✅ EXPECT JSON, not HTML
                    timeout: 60000,

                    success: function (response, status, xhr) {
                        console.log('Upload success!', response);

                        if (response.success) {
                            // Bukti backend: tampilkan hash & signature di console
                            if (response.hash) {
                                console.log('Nilai hash:', response.hash);
                            }
                            if (response.signature) {
                                console.log('Nilai signature:', response.signature);
                            }
                            $('#force_replace').val('false');
                            // ✅ Show success message
                            showSuccessAlert(response.message);

                            // Clear form
                            form[0].reset();
                            $('#hiddenPassphrase').val('');

                            // Reload page after 2 seconds to show updated table
                            setTimeout(() => {
                                location.reload();
                            }, 2000);
                        } else {
                            showErrorAlert(response.message);
                            resetUploadButton($btn);
                        }
                    },

                    error: function (xhr, status, error) {
                        console.error('Upload error:', {
                            status: xhr.status,
                            statusText: xhr.statusText,
                            error: error,
                            response: xhr.responseJSON
                        });

                        // ⛔ Dokumen sudah ada (Business Rule Violation)
                        if (xhr.status === 409 && xhr.responseJSON?.code === 'DOCUMENT_ALREADY_EXISTS') {
                            Swal.fire({
                                title: 'Dokumen Sudah Ada',
                                text: xhr.responseJSON.message,
                                icon: 'warning',
                                showCancelButton: true,
                                confirmButtonText: 'Ya, Ganti Dokumen',
                                cancelButtonText: 'Batal',
                                reverseButtons: true
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    // Set flag replace
                                    $('#force_replace').val('true');

                                    // Kirim ulang request
                                    $('#confirmUploadBtn').trigger('click');
                                } else {
                                    resetUploadButton($('#confirmUploadBtn'));
                                }
                            });

                            return; // ⛔ STOP error handler lain
                        }

                        let errorMsg = null;

                        // Parse JSON error response
                        if (xhr.responseJSON) {
                            if (xhr.responseJSON.errors) {
                                // Validation errors
                                errorMsg = '<strong>Validasi Gagal:</strong><br>';
                                $.each(xhr.responseJSON.errors, function (field, messages) {
                                    if (messages && messages.length > 0) {
                                        errorMsg += '- ' + messages[0] + '<br>';
                                    }
                                });
                            } else if (xhr.responseJSON.message) {
                                // Direct error message
                                errorMsg = xhr.responseJSON.message;
                            }
                        } else {
                            // Fallback error messages
                            if (xhr.status === 500) {
                                errorMsg =
                                    'Terjadi kesalahan di server. Silakan hubungi administrator.';
                            } else if (xhr.status === 403) {
                                errorMsg = 'Akses ditolak. Anda tidak memiliki izin.';
                            } else if (xhr.status === 404) {
                                errorMsg = 'Data tidak ditemukan.';
                            } else {
                                errorMsg = 'Gagal mengunggah dokumen: ' + error;
                            }
                        }

                        // Show error alert
                        if (errorMsg) {
                            showErrorAlert(errorMsg);
                        }

                        // Reset button
                        resetUploadButton($btn);
                    }
                });
            });

            $('.view-blockchain-btn').on('click', function () {
                const id = $(this).data('doc-id');

                $.get(`/documents/${id}/blockchain-data`, function (res) {
                    $('#blockchainModalBody').text(JSON.stringify(res, null, 4));
                    $('#blockchainModal').modal('show');
                });
            });


            // ✅ HELPER: Show success alert
            function showSuccessAlert(message) {
                const alertHtml = `
                                                                            <div class="alert alert-success alert-dismissible fade show" role="alert" style="margin-top: 15px;">
                                                                                <i class="bi bi-check-circle me-2"></i>${message}
                                                                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                                                            </div>
                                                                        `;

                $('#ajaxAlertContainer').html(alertHtml);
                $('html, body').animate({
                    scrollTop: 0
                }, 300);
            }

            // Validate file immediately when selected (optional quick feedback)
            const fileEl = document.getElementById('file');
            if (fileEl) {
                fileEl.addEventListener('change', function () {
                    const file = this.files?.[0];
                    if (!file) return;
                    const isPdf = file.type === 'application/pdf' || /\.pdf$/i.test(file.name);
                    if (!isPdf) {
                        showErrorAlert('File harus dalam bentuk PDF.');
                        this.value = '';
                        return;
                    }
                    const maxSize = 2 * 1024 * 1024; // 2MB
                    if (file.size > maxSize) {
                        showErrorAlert('Ukuran file maksimal 2MB.');
                        this.value = '';
                        return;
                    }
                });
            }

            // ✅ HELPER: Show error alert
            function showErrorAlert(message) {
                const alertHtml = `
                                                                            <div class="alert alert-danger alert-dismissible fade show" role="alert" style="margin-top: 15px;">
                                                                                ${message}
                                                                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                                                            </div>
                                                                        `;

                // Inject alert ke container
                $('#ajaxAlertContainer').html(alertHtml);

                // Scroll to top
                $('html, body').animate({
                    scrollTop: 0
                }, 300);

                // Auto dismiss after 15 seconds
                setTimeout(() => {
                    $('#ajaxAlertContainer .alert').fadeOut(function () {
                        $(this).remove();
                    });
                }, 15000);
            }

            // ✅ HELPER: Show duplicate details via SweetAlert2
            function showDuplicateAlert(existing, message) {
                const createdAt = existing?.createdAt ? new Date(existing.createdAt).toLocaleString() : '-';
                const docType = existing?.documentType || existing?.docType || '-';
                const filename = existing?.filename || '-';
                const hash = existing?.hash || existing?.documentHash || '-';
                const status = existing?.status || '-';
                const studentId = existing?.studentId || '-';
                const institutionId = existing?.institutionId || '-';

                const html = `
                                                        <div class="text-start">
                                                            <p class="mb-2">${message || 'Dokumen sudah tercatat di blockchain sebelumnya.'}</p>
                                                            <ul class="mb-0" style="list-style:none; padding-left:0;">
                                                                <li><strong>Tanggal Tercatat:</strong> ${createdAt}</li>
                                                                <li><strong>Nama File:</strong> ${filename}</li>
                                                                <li><strong>Jenis Dokumen:</strong> ${docType}</li>
                                                                <li><strong>Status:</strong> ${status}</li>
                                                                <li><strong>Hash:</strong> <code>${hash}</code></li>
                                                                <li><strong>Student ID:</strong> ${studentId}</li>
                                                                <li><strong>Institution ID:</strong> ${institutionId}</li>
                                                            </ul>
                                                        </div>
                                                    `;

                Swal.fire({
                    title: 'Dokumen Sudah Tercatat',
                    html: html,
                    icon: 'info',
                    confirmButtonText: 'Tutup'
                });
            }

            // ✅ HELPER: Reset button
            function resetUploadButton($btn) {
                $btn.prop('disabled', false).html(
                    '<i class="bi bi-lock-fill me-2"></i>Verifikasi & Unggah'
                );
            }

            // Modal reset
            $('#passphraseModal').on('hidden.bs.modal', function () {
                $('#passphrase_input').val('');
                $('#passphrase-alert').addClass('d-none');
                resetUploadButton($('#confirmUploadBtn'));
            });

            // Blockchain verification button
            $('.send-blockchain-btn').on('click', function () {
                const button = $(this);
                const docId = button.data('doc-id');
                Swal.fire({
                    title: 'Konfirmasi Verifikasi',
                    text: 'Apakah Anda yakin? Data pada blockchain bersifat permanen dan tidak dapat dimodifikasi.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#0d6efd',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya, lanjutkan',
                    cancelButtonText: 'Batal',
                    reverseButtons: true
                }).then((result) => {
                    if (!result.isConfirmed) {
                        return;
                    }

                    button.prop('disabled', true).html(
                        '<i class="bi bi-hourglass-split me-1"></i>Memproses...');

                    console.log('Sending to blockchain:', {
                        docId: docId,
                        hash: button.data('hash'),
                        signature: button.data('signature'),
                        pubkey: button.data('pubkey')
                    });

                    $.ajax({
                        url: `/documents/${docId}/send-blockchain`,
                        method: "POST",
                        data: {
                            _token: "{{ csrf_token() }}",
                        },
                        dataType: "json",
                        success: function (response) {
                            console.log("Blockchain response:", response);

                            if (response.success) {
                                showSuccessAlert(response.message);

                                setTimeout(() => {
                                    location.reload();
                                }, 10000);
                            } else if (response?.status === 'duplicate' && response?.existing) {
                                showDuplicateAlert(response.existing, response.message);
                                button.prop('disabled', false).html(
                                    '<i class="bi bi-shield-check me-1"></i>Verifikasi'
                                );
                            } else {
                                showErrorAlert(response.message || 'Gagal memverifikasi ke blockchain.');
                                button.prop('disabled', false).html(
                                    '<i class="bi bi-shield-check me-1"></i>Verifikasi'
                                );
                            }
                        },
                        error: function (xhr) {
                            console.error("Blockchain error:", xhr);

                            let msg = "Gagal memverifikasi ke blockchain.";

                            // Handle duplicate specifically (HTTP 409)
                            if (xhr.status === 409 && xhr.responseJSON && xhr.responseJSON.status === 'duplicate') {
                                showDuplicateAlert(xhr.responseJSON.existing || {}, xhr.responseJSON.message);
                                button.prop('disabled', false).html(
                                    '<i class="bi bi-shield-check me-1"></i>Verifikasi'
                                );
                                return;
                            }

                            if (xhr.responseJSON && xhr.responseJSON.message) {
                                msg = xhr.responseJSON.message;
                            }

                            showErrorAlert(msg);

                            button.prop('disabled', false).html(
                                '<i class="bi bi-shield-check me-1"></i>Verifikasi'
                            );
                        }
                    });
                });
            });

            // Delete confirmation with SweetAlert2
            $(document).on('submit', '.doc-delete-form', function (e) {
                e.preventDefault();
                const form = this;

                Swal.fire({
                    title: 'Hapus dokumen ini?',
                    text: 'Tindakan tidak dapat dibatalkan.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya, hapus',
                    cancelButtonText: 'Batal',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    </script>
@endpush