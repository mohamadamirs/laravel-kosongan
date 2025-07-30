<aside class="main-sidebar sidebar-dark-primary elevation-4 ">
    @php
        // Logika cerdas untuk mendapatkan data dan foto profil yang relevan
        $user = Auth::user();
        $fotoUrl = 'https://adminlte.io/themes/v3/dist/img/user2-160x160.jpg'; // Foto default

        if ($user->role == 'peserta' && $user->peserta) {
            $profile = $user->peserta;
            // Menggunakan kolom 'gambar' dan path yang benar untuk peserta
            if ($profile->gambar && $profile->gambar !== 'default.png') {
                $fotoUrl = asset('storage/peserta/fotos/' . $profile->gambar);
            }
        } elseif (in_array($user->role, ['pembimbing_instansi', 'pembimbing_kominfo'])) {
            $profile = $user->pembimbingInstansi ?? $user->pembimbingKominfo;
            // Menggunakan kolom 'foto' dan path yang benar untuk pembimbing
            if ($profile && $profile->foto) {
                $fotoUrl = asset('storage/avatars/' . $profile->foto);
            }
        }
        // Untuk admin, akan menggunakan foto default karena tidak ada profil foto
    @endphp

    <!-- Brand Logo Section (Diganti dengan Profil Pengguna) -->
    <a href="{{ route('profile.show') }}" class="brand-link">
        <img src="{{ $fotoUrl }}" alt="Foto Profil" class="brand-image elevation-3">
        <span class="brand-text font-weight-light">{{ $user->name }}</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                data-accordion="false">

                {{-- ====================================================== --}}
                {{--                 MENU KHUSUS UNTUK ADMIN                --}}
                {{-- ====================================================== --}}
                @if ($user->role == 'admin')
                    <li class="nav-item">
                        <a href="{{ route('admin.dashboard') }}"
                            class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>Dashboard</p>
                        </a>
                    </li>
                    <li class="nav-header">MANAJEMEN UTAMA</li>
                    <li class="nav-item">
                        <a href="{{ route('admin.users.index') }}"
                            class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-users-cog"></i>
                            <p>Pengguna</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.surat-masuk.index') }}"
                            class="nav-link {{ request()->routeIs('admin.surat-masuk.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-envelope-open-text"></i>
                            <p>Persetujuan Masuk</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.berita.index') }}"
                            class="nav-link {{ request()->routeIs('admin.berita.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-newspaper"></i>
                            <p>Berita & Publikasi</p>
                        </a>
                    </li>
                    <li class="nav-header">DATA MASTER</li>
                    <li class="nav-item">
                        <a href="{{ route('admin.ruangan.index') }}"
                            class="nav-link {{ request()->routeIs('admin.ruangan.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-door-open"></i>
                            <p>Ruangan</p>
                        </a>
                    </li>
                    <li class="nav-header">AKUN</li>
                    <li class="nav-item">
                        <a href="{{ route('profile.show') }}"
                            class="nav-link {{ request()->routeIs('profile.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-user-circle"></i>
                            <p>Profil Saya</p>
                        </a>
                    </li>
                @endif

                {{-- ====================================================== --}}
                {{--                MENU KHUSUS UNTUK PESERTA               --}}
                {{-- ====================================================== --}}
                @if ($user->role == 'peserta')
                    <li class="nav-item">
                        <a href="{{ route('peserta.dashboard') }}"
                            class="nav-link {{ request()->routeIs('peserta.dashboard') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>Dashboard</p>
                        </a>
                    </li>
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
                        <a href="{{ route('peserta.absensi.scan.show') }}"
                            class="nav-link {{ request()->routeIs('peserta.absensi.scan.show') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-qrcode"></i>
                            <p>Absen via Scan</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('peserta.izin-cuti.index') }}"
                            class="nav-link {{ request()->routeIs('peserta.izin-cuti.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-file-alt"></i>
                            <p>Izin / Cuti</p>
                        </a>
                    </li>
                    <li class="nav-header">AKUN</li>
                    <li class="nav-item">
                        <a href="{{ route('profile.show') }}"
                            class="nav-link {{ request()->routeIs('profile.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-user-circle"></i>
                            <p>Profil Saya</p>
                        </a>
                    </li>
                @endif

                {{-- ====================================================== --}}
                {{--           MENU KHUSUS UNTUK KEDUA PEMBIMBING           --}}
                {{-- ====================================================== --}}
                @if (in_array($user->role, ['pembimbing_instansi', 'pembimbing_kominfo']))
                    @php
                        $prefix = $user->role == 'pembimbing_instansi' ? 'pembimbing.instansi.' : 'pembimbing.kominfo.';
                    @endphp
                    <li class="nav-header">MENU PEMBIMBING</li>
                    <li class="nav-item">
                        <a href="{{ route($prefix . 'dashboard') }}"
                            class="nav-link {{ request()->routeIs($prefix . 'dashboard') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>Dashboard</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route($prefix . 'peserta.index') }}"
                            class="nav-link {{ request()->routeIs([$prefix . 'peserta.index', $prefix . 'peserta.show']) ? 'active' : '' }}">
                            <i class="nav-icon fas fa-users"></i>
                            <p>Peserta Bimbingan</p>
                        </a>
                    </li>
                    <li class="nav-header">AKUN</li>
                    <li class="nav-item">
                        <a href="{{ route('profile.show') }}"
                            class="nav-link {{ request()->routeIs('profile.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-user-circle"></i>
                            <p>Profil Saya</p>
                        </a>
                    </li>
                @endif
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
