<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{ route('dashboard') }}" class="brand-link">
        <img src="https://adminlte.io/themes/v3/dist/img/AdminLTELogo.png" alt="AdminLTE Logo"
            class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">Aplikasi Magang</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                {{-- Anda bisa mengganti ini dengan foto profil user jika ada --}}
                <img src="https://adminlte.io/themes/v3/dist/img/user2-160x160.jpg" class="img-circle elevation-2"
                    alt="User Image">
            </div>
            <div class="info">
                <a href="#" class="d-block">{{ Auth::user()->name }}</a>
                <small class="text-muted">{{ ucfirst(str_replace('_', ' ', Auth::user()->role)) }}</small>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                data-accordion="false">

                <!-- MENU UMUM UNTUK SEMUA ROLE -->
                <li class="nav-item">
                    {{-- `route('dashboard')` akan otomatis mengarah ke pintu gerbang yang benar --}}
                    <a href="{{ route('dashboard') }}"
                        class="nav-link {{ request()->is('*/dashboard') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Dashboard</p>
                    </a>
                </li>


                {{-- ====================================================== --}}
                {{--                 MENU KHUSUS UNTUK ADMIN                --}}
                {{-- ====================================================== --}}
                @if (Auth::user()->role == 'admin')
                    <li class="nav-header">DATA MASTER</li>
                    
                    <li class="nav-item">
                        <a href="{{ route('admin.users.index') }}"
                            class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-users-cog"></i>
                            <p>Pengguna</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.ruangan.index') }}"
                            class="nav-link {{ request()->routeIs('admin.ruangan.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-door-open"></i>
                            <p>Ruangan</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.berita.index') }}"
                            class="nav-link {{ request()->routeIs('admin.berita.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-newspaper"></i>
                            <p>Berita</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.surat-masuk.index') }}"
                            class="nav-link {{ request()->routeIs('admin.surat-masuk.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-envelope"></i>
                            <p>Surat Masuk</p>
                        </a>
                    </li>
                @endif


                {{-- ====================================================== --}}
                {{--                MENU KHUSUS UNTUK PESERTA               --}}
                {{-- ====================================================== --}}
                @if (Auth::user()->role == 'peserta')
                    <li class="nav-header">AKTIVITAS SAYA</li>
                    <li class="nav-item">
                        <a href="{{ route('peserta.kegiatan.index') }}"
                            class="nav-link {{ request()->routeIs('peserta.kegiatan.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-tasks"></i>
                            <p>Kegiatan Harian</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('peserta.absensi.index') }}"
                            class="nav-link {{ request()->routeIs('peserta.absensi.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-calendar-check"></i>
                            <p>Absensi</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('peserta.izin-cuti.index') }}"
                            class="nav-link {{ request()->routeIs('peserta.izin-cuti.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-file-alt"></i>
                            <p>Izin / Cuti</p>
                        </a>
                    </li>
                @endif


                {{-- ====================================================== --}}
                {{--           MENU KHUSUS UNTUK KEDUA PEMBIMBING           --}}
                {{-- ====================================================== --}}
                @if (in_array(Auth::user()->role, ['pembimbing_instansi', 'pembimbing_kominfo']))
                    <li class="nav-header">MONITORING</li>
                    <li class="nav-item">
                        {{-- Ganti '#' dengan route yang sesuai nanti --}}
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-chalkboard-teacher"></i>
                            <p>Peserta Bimbingan</p>
                        </a>
                    </li>
                @endif

            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
