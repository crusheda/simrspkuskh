<header class="app-topbar">
    <div class="topbar-left">
        <button
            class="icon-button sidebar-toggle"
            id="sidebarToggle"
            aria-label="Buka navigasi"
        >
            <i class="bi bi-list"></i>
        </button>

        <div>
            <span class="breadcrumb-kicker">
                SISTEM INFORMASI RUMAH SAKIT
            </span>

            <div class="breadcrumb-title">
                @yield('page-title', 'Rekam Medis Elektronik')
            </div>
        </div>
    </div>

    <div class="topbar-actions">
        <span class="topbar-date d-none d-md-inline-flex">
            <i class="bi bi-calendar3"></i>
            {{ now()->translatedFormat('l, d F Y') }}
        </span>

        <button
            class="icon-button"
            id="themeToggleTop"
            aria-label="Ganti tema"
        >
            <i class="bi bi-moon-stars"></i>
        </button>

        <div class="dropdown">
            <button
                class="user-menu dropdown-toggle"
                data-bs-toggle="dropdown"
            >
                <span class="user-avatar">
                    {{ mb_substr(Auth::user()->NAMA ?? (Auth::user()->name ?? 'U'), 0, 1) }}
                </span>

                <span class="user-menu-copy d-none d-sm-flex">
                    <strong>
                        {{ Auth::user()->NAMA ?? (Auth::user()->name ?? 'Pengguna') }}
                    </strong>

                    <small>
                        {{ Auth::user()->NIP ?? 'Pengguna SIRMED' }}
                    </small>
                </span>
            </button>

            <ul class="dropdown-menu dropdown-menu-end user-dropdown">
                <li class="dropdown-header">Akun pengguna</li>

                <li>
                    <hr class="dropdown-divider">
                </li>

                <li>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf

                        <button type="submit" class="dropdown-item text-danger">
                            <i class="bi bi-box-arrow-right me-2"></i>
                            Keluar
                        </button>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</header>
