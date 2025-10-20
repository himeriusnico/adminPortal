<nav class="col-md-3 col-lg-2 d-md-block sidebar bg-dark">
    <div class="position-sticky pt-3">
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link {{ request()->is('dashboard') ? 'active' : '' }}" href="{{ url('/dashboard') }}">
                    <i class="bi bi-speedometer2 me-2"></i>
                    Dashboard
                </a>
            </li>

            <!-- Menu berdasarkan tipe pengguna -->
            @if (Auth::user()->user_type === 'admin')
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('institutions*') ? 'active' : '' }}"
                        href="{{ route('institutions.index') }}">
                        <i class="bi bi-building me-2"></i>
                        Institusi
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('pegawai*') ? 'active' : '' }}" href="#">
                        <i class="bi bi-people me-2"></i>
                        Kelola Pegawai
                    </a>
                </li>
            @endif

            @if (in_array(Auth::user()->user_type, ['admin', 'pegawai']))
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('students*') ? 'active' : '' }}"
                        href="{{ route('students.index') }}">
                        <i class="bi bi-person-badge me-2"></i>
                        Mahasiswa
                    </a>
                </li>
            @endif

            @if (Auth::user()->user_type === 'pegawai')
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('upload*') ? 'active' : '' }}" href="#">
                        <i class="bi bi-upload me-2"></i>
                        Unggah Dokumen
                    </a>
                </li>
            @endif

            @if (Auth::user()->user_type === 'student')
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('my-documents*') ? 'active' : '' }}" href="#">
                        <i class="bi bi-file-earmark-text me-2"></i>
                        Dokumen Saya
                    </a>
                </li>
            @endif

            <li class="nav-item">
                <a class="nav-link {{ request()->is('settings*') ? 'active' : '' }}" href="#">
                    <i class="bi bi-gear me-2"></i>
                    Pengaturan
                </a>
            </li>
        </ul>
    </div>
</nav>
