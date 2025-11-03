<nav class="col-md-3 col-lg-2 d-md-block sidebar bg-dark">
    <div class="position-sticky pt-3">
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link {{ request()->is('dashboard') ? 'active' : '' }}" href="{{ url('/dashboard') }}">
                    <i class="bi bi-speedometer2 me-2"></i>
                    Dashboard
                </a>
            </li>

            @if (Auth::user()->role->name === 'super_admin')
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('institutions*') ? 'active' : '' }}"
                        href="{{ route('institutions.index') }}">
                        <i class="bi bi-building me-2"></i>
                        Institusi
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('users*') ? 'active' : '' }}" href="#">
                        <i class="bi bi-people me-2"></i>
                        Kelola Pengguna
                    </a>
                </li>
            @endif

            @if (Auth::user()->role->name === 'admin')
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('students*') ? 'active' : '' }}"
                        href="{{ route('students.index') }}">
                        <i class="bi bi-person-badge me-2"></i>
                        Mahasiswa
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('documents*') ? 'active' : '' }}"
                        href="{{ route('documents.index') }}">
                        <i class="bi bi-upload me-2"></i>
                        Unggah Dokumen
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('institution*') ? 'active' : '' }}" href="#">
                        <i class="bi bi-gear me-2"></i>
                        Pengaturan Institusi
                    </a>
                </li>
            @endif

            @if (Auth::user()->role->name === 'student')
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('my-documents*') ? 'active' : '' }}"
                        href="{{ route('students.show', Auth::user()->student->id) }}">
                        <i class="bi bi-file-earmark-text me-2"></i>
                        Dokumen Saya
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('verification*') ? 'active' : '' }}" href="#">
                        <i class="bi bi-qr-code me-2"></i>
                        Verifikasi Dokumen
                    </a>
                </li>
            @endif

            <!-- Menu umum untuk semua role -->
            <li class="nav-item">
                <a class="nav-link {{ request()->is('settings*') ? 'active' : '' }}" href="#">
                    <i class="bi bi-gear me-2"></i>
                    Pengaturan
                </a>
            </li>
        </ul>
    </div>
</nav>
