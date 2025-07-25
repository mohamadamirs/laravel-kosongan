@extends('layouts.app')

@section('title', 'Profil Saya')

@push('css')
    {{-- CSS Kustom Khusus untuk Halaman Profil Ini --}}
    <style>
        .section {
            padding: 50px 0;
            position: relative;
        }

        .gray-bg {
            background-color: #f5f5f5;
            border-radius: 10px;
        }

        .about-text h3 {
            font-size: 45px;
            font-weight: 700;
            margin: 0 0 6px;
        }

        @media (max-width: 767px) {
            .about-text h3 {
                font-size: 35px;
            }
        }

        .about-text h6 {
            font-weight: 600;
            margin-bottom: 15px;
        }

        @media (max-width: 767px) {
            .about-text h6 {
                font-size: 18px;
            }
        }

        .about-text p {
            font-size: 18px;
            max-width: 450px;
        }

        .about-text p mark {
            font-weight: 600;
            color: #20247b;
        }

        .about-list {
            padding-top: 10px;
        }

        .about-list .media {
            padding: 5px 0;
            display: flex;
            align-items: center;
        }

        .about-list label {
            color: #20247b;
            font-weight: 600;
            width: 100px;
            margin: 0;
            position: relative;
        }

        .about-list label:after {
            content: ":";
            position: absolute;
            top: 0;
            bottom: 0;
            right: 11px;
            margin: auto;
        }

        .about-list p {
            margin: 0;
            font-size: 15px;
        }

        @media (max-width: 991px) {
            .about-avatar {
                margin-top: 30px;
            }
        }

        .about-avatar img {
            border-radius: 10px;
            width: 100%;
        }

        .counter {
            padding: 22px 20px;
            background: #ffffff;
            border-radius: 10px;
            box-shadow: 0 0 30px rgba(31, 45, 61, 0.125);
        }

        .counter .count-data {
            margin-top: 10px;
            margin-bottom: 10px;
        }

        .counter .count {
            font-weight: 700;
            color: #20247b;
            margin: 0 0 5px;
        }

        .counter p {
            font-weight: 600;
            margin: 0;
        }

        .theme-color {
            color: #fc5356;
        }

        .dark-color {
            color: #20247b;
        }

        .about-avatar {
            /* Kita buat wadahnya menjadi persegi */
            width: 300px;
            height: 300px;
            /* Ini adalah properti kunci yang membuatnya menjadi lingkaran */
            border-radius: 50%;
            /* Ini menyembunyikan bagian gambar yang keluar dari area lingkaran */
            overflow: hidden;
            /* Efek bayangan agar lebih menonjol */
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
            /* Menengahkan wadah di dalam kolomnya */
            margin: 30px auto 0;
        }

        .about-avatar img {
            /* Pastikan gambar mengisi seluruh wadah lingkaran */
            width: 100%;
            height: 100%;
            /* Properti paling penting: mencegah gambar menjadi gepeng/terdistorsi.
               Gambar akan diskalakan untuk menutupi area, dan sisanya akan dipotong. */
            object-fit: cover;
        }
    </style>
@endpush

@section('content-header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0">Profil Saya</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Profil Saya</li>
            </ol>
        </div>
    </div>
@endsection

@section('content')
    <section class="section about-section gray-bg" id="about">
        <div class="container">
            <div class="row align-items-center flex-row-reverse">
                <div class="col-lg-6">
                    <div class="about-text go-to">
                        <h3 class="dark-color">{{ $user->name }}</h3>

                        {{-- Deskripsi dinamis berdasarkan peran --}}
                        @if (in_array($user->role, ['pembimbing_instansi', 'pembimbing_kominfo']))
                            @php $profile = $user->pembimbingInstansi ?? $user->pembimbingKominfo; @endphp
                            <h6 class="theme-color lead">{{ $profile->bidang ?? 'Bidang Belum Diisi' }}</h6>
                            <p>Informasi detail akun dan profil Anda sebagai
                                {{ ucfirst(str_replace('_', ' ', $user->role)) }}.</p>
                        @elseif($user->role == 'peserta' && $user->peserta)
                            @php $profile = $user->peserta; @endphp
                            <h6 class="theme-color lead">{{ $profile->jurusan ?? 'Jurusan Belum Diisi' }}</h6>
                            <p>Informasi detail akun dan profil Anda sebagai Peserta Magang.</p>
                        @else
                            <h6 class="theme-color lead">Administrator</h6>
                            <p>Informasi detail akun Anda sebagai Administrator sistem.</p>
                        @endif

                        {{-- List Data Diri Dinamis --}}
                        <div class="row about-list">
                            <div class="col-md-6">
                                <div class="media"><label>Peran</label>
                                    <p>{{ ucfirst(str_replace('_', ' ', $user->role)) }}</p>
                                </div>
                                @if (isset($profile->telepon))
                                    <div class="media"><label>Telepon</label>
                                        <p>{{ $profile->telepon }}</p>
                                    </div>
                                @endif
                                @if (isset($profile->lahir) && $profile->lahir)
                                    <div class="media"><label>Lahir</label>
                                        <p>{{ \Carbon\Carbon::parse($profile->lahir)->format('d F Y') }}</p>
                                    </div>
                                @endif
                                @if (isset($profile->asal_sekolah))
                                    <div class="media"><label>Asal</label>
                                        <p>{{ $profile->asal_sekolah }}</p>
                                    </div>
                                @endif
                            </div>
                            <div class="col-md-6">
                                <div class="media"><label>E-mail</label>
                                    <p>{{ $user->email }}</p>
                                </div>
                                @if (isset($profile->alamat))
                                    <div class="media"><label>Alamat</label>
                                        <p>{{ $profile->alamat }}</p>
                                    </div>
                                @endif
                                @if (isset($profile->nisn) && $profile->nisn)
                                    <div class="media"><label>NISN/NIM</label>
                                        <p>{{ $profile->nisn }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="pt-4">
                            <a href="{{ route('profile.edit') }}" class="btn btn-primary">
                                <i class="fas fa-edit"></i> Edit Profil & Password
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="about-avatar">
                        @php
                            // --- LOGIKA CERDAS UNTUK MENAMPILKAN FOTO YANG BENAR ---
                            $fotoUrl = 'https://bootdey.com/img/Content/avatar/avatar7.png'; // Foto default
                            if ($user->role == 'peserta' && $user->peserta) {
                                $profile = $user->peserta;
                                if ($profile->gambar && $profile->gambar !== 'default.png') {
                                    $fotoUrl = asset('storage/peserta/fotos/' . $profile->gambar);
                                }
                            } elseif (in_array($user->role, ['pembimbing_instansi', 'pembimbing_kominfo'])) {
                                $profile = $user->pembimbingInstansi ?? $user->pembimbingKominfo;
                                if ($profile && $profile->foto) {
                                    $fotoUrl = asset('storage/avatars/' . $profile->foto);
                                }
                            }
                        @endphp
                        <img src="{{ $fotoUrl }}" title="Foto Profil" alt="Foto Profil">
                    </div>
                </div>
            </div>

            {{-- Counter dinamis --}}
            @if (!empty($counterData))
                <div class="counter mt-5">
                    <div class="row">
                        @foreach ($counterData as $data)
                            <div class="col-6 col-lg-3">
                                <div class="count-data text-center">
                                    <h6 class="count h2">{{ $data['value'] }}</h6>
                                    <p class="m-0px font-w-600">{{ $data['label'] }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </section>
@endsection
