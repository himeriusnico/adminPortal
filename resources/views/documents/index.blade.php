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
                        <select class="form-select @error('document_type_id') is-invalid @enderror" id="document_type_id"
                            name="document_type_id" required>
                            <option value="" disabled selected>-- Pilih Jenis --</option>
                            @foreach ($documentTypes as $type)
                                <option value="{{ $type->id }}"
                                    {{ old('document_type_id') == $type->id ? 'selected' : '' }}>
                                    {{ $type->name }} </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="file" class="form-label">File PDF (Maks 2MB) *</label>
                        <input class="form-control @error('file') is-invalid @enderror" type="file" id="file"
                            name="file" accept=".pdf" required>
                    </div>
                </div>

                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#passphraseModal">
                    <i class="bi bi-upload me-2"></i>Unggah
                </button>

                <input type="hidden" name="passphrase" id="hiddenPassphrase" value="">
            </form>
        </div>
    </div>

    <div class="modal fade" id="passphraseModal" tabindex="-1" aria-labelledby="passphraseModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="passphraseModalLabel">Konfirmasi Unggah & Tanda Tangan Digital</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Masukkan **Passphrase Kunci Privat Institusi** Anda untuk mendekripsi kunci dan menandatangani
                        dokumen ini secara digital sebelum diunggah.</p>
                    <div class="mb-3">
                        <label for="passphrase_input" class="form-label">Passphrase *</label>
                        <input type="password" class="form-control" id="passphrase_input" required autocomplete="off">
                    </div>
                    <div id="passphrase-alert" class="alert alert-danger d-none" role="alert">
                        Passphrase tidak boleh kosong.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary" id="confirmUploadBtn">
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
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($documents as $doc)
                            <tr>
                                <td>{{ $doc->student->user->name ?? 'N/A' }}</td>
                                <td><code>{{ $doc->student->student_id ?? 'N/A' }}</code></td>
                                <td>
                                    <span
                                        class="badge bg-secondary">{{ $doc->documentType->name ?? 'Jenis Hilang' }}</span>
                                </td>
                                <td><i class="bi bi-file-pdf text-danger me-2"></i>{{ $doc->filename }}</td>
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
                                            <span class="badge bg-success">Terverifikasi</span>

                                            <button class="btn btn-sm btn-info view-blockchain-btn"
                                                data-doc-id="{{ $doc->id }}">
                                                <i class="bi bi-eye me-1"></i> View on Blockchain
                                            </button>
                                        </div>
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
        $(document).ready(function() {
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
            $('#confirmUploadBtn').on('click', function() {
                const passphrase = $('#passphrase_input').val();
                const form = $('#documentUploadForm');

                console.log('Passphrase modal value:', passphrase);
                console.log('Passphrase length:', passphrase.length);

                if (passphrase === 0 || passphrase === '') {
                    $('#passphrase-alert')
                        .removeClass('d-none')
                        .text('Passphrase tidak boleh kosong.');
                    console.log('Validation failed: Passphrase is empty.');
                    return;
                }

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

                    success: function(response, status, xhr) {
                        console.log('Upload success!', response);

                        if (response.success) {
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

                    error: function(xhr, status, error) {
                        console.error('Upload error:', {
                            status: xhr.status,
                            statusText: xhr.statusText,
                            error: error,
                            response: xhr.responseJSON
                        });

                        let errorMsg = null;

                        // Parse JSON error response
                        if (xhr.responseJSON) {
                            if (xhr.responseJSON.errors) {
                                // Validation errors
                                errorMsg = '<strong>Validasi Gagal:</strong><br>';
                                $.each(xhr.responseJSON.errors, function(field, messages) {
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

            $('.view-blockchain-btn').on('click', function() {
                const id = $(this).data('doc-id');

                $.get(`/documents/${id}/blockchain-data`, function(res) {
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
                    $('#ajaxAlertContainer .alert').fadeOut(function() {
                        $(this).remove();
                    });
                }, 15000);
            }

            // ✅ HELPER: Reset button
            function resetUploadButton($btn) {
                $btn.prop('disabled', false).html(
                    '<i class="bi bi-lock-fill me-2"></i>Verifikasi & Unggah'
                );
            }

            // Modal reset
            $('#passphraseModal').on('hidden.bs.modal', function() {
                $('#passphrase_input').val('');
                $('#passphrase-alert').addClass('d-none');
                resetUploadButton($('#confirmUploadBtn'));
            });

            // Blockchain verification button
            $('.send-blockchain-btn').on('click', function() {
                const button = $(this);
                const docId = button.data('doc-id');

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
                    success: function(response) {
                        console.log("Blockchain response:", response);

                        if (response.success) {
                            showSuccessAlert(response.message);

                            setTimeout(() => {
                                location.reload();
                            }, 1500);
                        } else {
                            showErrorAlert(response.message);
                            button.prop('disabled', false).html(
                                '<i class="bi bi-shield-check me-1"></i>Verifikasi'
                            );
                        }
                    },
                    error: function(xhr) {
                        console.error("Blockchain error:", xhr);

                        let msg = "Gagal memverifikasi ke blockchain.";

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
    </script>
@endpush
