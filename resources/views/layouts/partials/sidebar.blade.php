<nav class="col-md-3 col-lg-2 d-md-block sidebar bg-dark sticky">
    <div class="position-sticky pt-3">
        <!-- User Profile Section -->
        {{-- nanti dulu aje --}}
        {{-- <div class="sidebar-profile px-3 mb-4">
            <div class="d-flex align-items-center text-white">
                <div class="profile-avatar me-3">
                    <div class="avatar-circle bg-primary d-flex align-items-center justify-content-center">
                        <span class="fw-bold">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</span>
                    </div>
                </div>
                <div class="profile-info">
                    <h6 class="mb-0 fw-bold text-truncate" style="max-width: 150px;">{{ Auth::user()->name }}</h6>
                    <small class="text-muted">
                        @if (Auth::user()->role->name === 'super_admin')
                            <i class="bi bi-shield-check text-warning me-1"></i>Super Admin
                        @elseif(Auth::user()->role->name === 'admin')
                            <i class="bi bi-building text-info me-1"></i>Administrator
                        @else
                            <i class="bi bi-mortarboard text-success me-1"></i>Mahasiswa
                        @endif
                    </small>
                </div>
            </div>
        </div> --}}

        <!-- Navigation Menu -->
        <ul class="nav flex-column sidebar-nav">
            <!-- Dashboard -->
            <li class="nav-item">
                <a class="nav-link sidebar-link {{ request()->is('dashboard') ? 'active' : '' }}"
                    href="{{ url('/dashboard') }}">
                    <div class="sidebar-icon">
                        <i class="bi bi-speedometer2"></i>
                    </div>
                    <span class="sidebar-text">Dashboard</span>
                    @if (request()->is('dashboard'))
                        <span class="sidebar-active-indicator"></span>
                    @endif
                </a>
            </li>

            <!-- Super Admin Menu -->
            @if (Auth::user()->role->name === 'super_admin')
                <li class="nav-item">
                    <a class="nav-link sidebar-link {{ request()->is('institutions*') ? 'active' : '' }}"
                        href="{{ route('institutions.index') }}">
                        <div class="sidebar-icon">
                            <i class="bi bi-building"></i>
                        </div>
                        <span class="sidebar-text">Institusi</span>
                        @if (request()->is('institutions*'))
                            <span class="sidebar-active-indicator"></span>
                        @endif
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link sidebar-link {{ request()->is('users*') ? 'active' : '' }}" href="#">
                        <div class="sidebar-icon">
                            <i class="bi bi-people"></i>
                        </div>
                        <span class="sidebar-text">Kelola Pengguna</span>
                        @if (request()->is('users*'))
                            <span class="sidebar-active-indicator"></span>
                        @endif
                    </a>
                </li>
            @endif

            <!-- Admin Menu -->
            @if (Auth::user()->role->name === 'admin')
                <li class="nav-item">
                    <a class="nav-link sidebar-link {{ request()->is('students*') ? 'active' : '' }}"
                        href="{{ route('students.index') }}">
                        <div class="sidebar-icon">
                            <i class="bi bi-person-badge"></i>
                        </div>
                        <span class="sidebar-text">Mahasiswa</span>
                        @if (request()->is('students*'))
                            <span class="sidebar-active-indicator"></span>
                        @endif
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link sidebar-link {{ request()->is('documents*') ? 'active' : '' }}"
                        href="{{ route('documents.index') }}">
                        <div class="sidebar-icon">
                            <i class="bi bi-cloud-upload"></i>
                        </div>
                        <span class="sidebar-text">Unggah Dokumen</span>
                        @if (request()->is('documents*'))
                            <span class="sidebar-active-indicator"></span>
                        @endif
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link sidebar-link {{ request()->is('institution*') ? 'active' : '' }}"
                        href="{{ route('institutions.settings') }}">
                        <div class="sidebar-icon">
                            <i class="bi bi-gear-fill"></i>
                        </div>
                        <span class="sidebar-text">Pengaturan Institusi</span>
                        @if (request()->is('institution*'))
                            <span class="sidebar-active-indicator"></span>
                        @endif
                    </a>
                </li>
            @endif

            <!-- Student Menu -->
            @if (Auth::user()->role->name === 'student')
                <li class="nav-item">
                    <a class="nav-link sidebar-link {{ request()->is('my-documents*') ? 'active' : '' }}"
                        href="{{ route('students.show', Auth::user()->student->id) }}">
                        <div class="sidebar-icon">
                            <i class="bi bi-folder2-open"></i>
                        </div>
                        <span class="sidebar-text">Dokumen Saya</span>
                        @if (request()->is('my-documents*'))
                            <span class="sidebar-active-indicator"></span>
                        @endif
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link sidebar-link {{ request()->is('verification*') ? 'active' : '' }}"
                        href="#">
                        <div class="sidebar-icon">
                            <i class="bi bi-patch-check"></i>
                        </div>
                        <span class="sidebar-text">Verifikasi Dokumen</span>
                        @if (request()->is('verification*'))
                            <span class="sidebar-active-indicator"></span>
                        @endif
                    </a>
                </li>
            @endif

            <!-- Common Menu for All Roles -->
            <li class="nav-item">
                <a class="nav-link sidebar-link {{ request()->is('settings*') ? 'active' : '' }}" href="#">
                    <div class="sidebar-icon">
                        <i class="bi bi-sliders"></i>
                    </div>
                    <span class="sidebar-text">Pengaturan</span>
                    @if (request()->is('settings*'))
                        <span class="sidebar-active-indicator"></span>
                    @endif
                </a>
            </li>
        </ul>

        <!-- Logout Section -->
        {{-- <div class="sidebar-footer mt-auto p-3">
            <form action="{{ route('logout') }}" method="POST" id="logout-form">
                @csrf
                <button type="button" class="btn btn-outline-light w-100 logout-btn" onclick="confirmLogout()">
                    <i class="bi bi-box-arrow-right me-2"></i>
                    Keluar
                </button>
            </form>
        </div> --}}
    </div>
</nav>

@push('styles')
    <style>
        .sidebar {
            background: linear-gradient(180deg, #2c3e50 0%, #34495e 100%) !important;
            min-height: 100vh;
            box-shadow: 3px 0 10px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .sidebar-profile {
            padding-top: 1rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            padding-bottom: 1.5rem;
        }

        .avatar-circle {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            font-size: 1.2rem;
            color: white;
        }

        .sidebar-nav {
            padding: 0 0.5rem;
        }

        .sidebar-link {
            color: #bdc3c7 !important;
            padding: 0.75rem 1rem;
            margin: 0.2rem 0;
            border-radius: 8px;
            transition: all 0.3s ease;
            position: relative;
            display: flex;
            align-items: center;
            text-decoration: none;
        }

        .sidebar-link:hover {
            color: #ecf0f1 !important;
            background: rgba(255, 255, 255, 0.1);
            transform: translateX(5px);
        }

        .sidebar-link.active {
            color: #ffffff !important;
            background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
            box-shadow: 0 4px 15px rgba(52, 152, 219, 0.3);
        }

        .sidebar-icon {
            width: 30px;
            text-align: center;
            margin-right: 12px;
            font-size: 1.1rem;
            transition: transform 0.3s ease;
        }

        .sidebar-link:hover .sidebar-icon {
            transform: scale(1.1);
        }

        .sidebar-text {
            flex: 1;
            font-weight: 500;
            font-size: 0.9rem;
        }

        .sidebar-active-indicator {
            width: 4px;
            height: 20px;
            background: #ffffff;
            border-radius: 2px;
            margin-left: auto;
            animation: pulse 2s infinite;
        }

        .sidebar-footer {
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        .logout-btn {
            border-radius: 8px;
            padding: 0.6rem;
            font-size: 0.9rem;
            transition: all 0.3s ease;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .logout-btn:hover {
            background: rgba(231, 76, 60, 0.1);
            border-color: #e74c3c;
            color: #e74c3c;
            transform: translateY(-2px);
        }

        /* Animation for active indicator */
        @keyframes pulse {
            0% {
                opacity: 1;
            }

            50% {
                opacity: 0.5;
            }

            100% {
                opacity: 1;
            }
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .sidebar {
                min-height: auto;
            }

            .profile-info h6 {
                font-size: 0.9rem;
            }

            .sidebar-text {
                font-size: 0.85rem;
            }
        }

        /* Scrollbar styling */
        .sidebar::-webkit-scrollbar {
            width: 5px;
        }

        .sidebar::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.1);
        }

        .sidebar::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.3);
            border-radius: 10px;
        }

        /* Role-specific color accents */
        .nav-item.super-admin .sidebar-link.active {
            background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%);
        }

        .nav-item.admin .sidebar-link.active {
            background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
        }

        .nav-item.student .sidebar-link.active {
            background: linear-gradient(135deg, #27ae60 0%, #2ecc71 100%);
        }
    </style>
@endpush

@push('scripts')
    <script>
        function confirmLogout() {
            Swal.fire({
                title: 'Konfirmasi Keluar',
                text: 'Apakah Anda yakin ingin keluar dari sistem?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Keluar',
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('logout-form').submit();
                }
            });
        }

        // Add hover effects with JavaScript for better performance
        document.addEventListener('DOMContentLoaded', function() {
            const sidebarLinks = document.querySelectorAll('.sidebar-link');

            sidebarLinks.forEach(link => {
                link.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateX(5px)';
                });

                link.addEventListener('mouseleave', function() {
                    if (!this.classList.contains('active')) {
                        this.style.transform = 'translateX(0)';
                    }
                });
            });

            // Add role-specific classes to menu items
            const role = '{{ Auth::user()->role->name }}';
            const navItems = document.querySelectorAll('.nav-item');

            navItems.forEach(item => {
                item.classList.add(role);
            });
        });
    </script>
@endpush
