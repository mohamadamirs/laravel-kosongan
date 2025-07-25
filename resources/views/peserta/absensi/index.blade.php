@extends('layouts.app')

@section('title', 'Absensi Harian')

@section('content-header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h1 class="m-0">Absensi Harian</h1>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('peserta.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Absensi</li>
        </ol>
    </div>
</div>
@endsection

@section('content')
<div class="row">
    <div class="col-md-4">
        {{-- KARTU AKSI UNTUK ABSENSI HARI INI --}}
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h5 class="card-title m-0">Absensi Hari Ini</h5>
            </div>
            <div class="card-body text-center">
                <p class="mb-2">Tanggal: <strong>{{ date('d F Y') }}</strong></p>

                {{-- ====================================================== --}}
                {{--     KETERANGAN JAM ABSENSI (SELALU TAMPIL)             --}}
                {{-- ====================================================== --}}
                <div class="alert alert-light border text-center my-3">
                    <p class="mb-0">
                        Absensi dibuka setiap hari mulai pukul <strong>{{ $jamMulaiAbsen->format('H:i') }}</strong> 
                        hingga pukul <strong>{{ $jamSelesaiAbsen->format('H:i') }}</strong>.
                    </p>
                </div>
                {{-- ====================================================== --}}
                

                {{-- Logika dinamis untuk menampilkan tombol atau pesan status --}}
                @if ($sudahAbsenHariIni)
                    {{-- Kondisi 1: Peserta SUDAH absen hari ini --}}
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle fa-2x"></i>
                        <h5 class="mt-2">Anda Sudah Absen</h5>
                        <p class="mb-0">Kehadiran Anda hari ini telah berhasil dicatat.</p>
                    </div>
                @elseif ($bisaAbsenSekarang)
                    {{-- Kondisi 2: Peserta BELUM absen DAN SEKARANG dalam jendela waktu --}}
                    <p class="card-text">Anda belum melakukan absensi untuk hari ini. Silakan klik tombol di bawah.</p>
                    <form action="{{ route('peserta.absensi.store') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-lg btn-success">
                            <i class="fas fa-fingerprint"></i> Catat Kehadiran Sekarang
                        </button>
                    </form>
                @else
                    {{-- Kondisi 3: Peserta BELUM absen TAPI SEKARANG di luar jendela waktu --}}
                    <div class="alert alert-danger">
                        <i class="fas fa-clock fa-2x"></i>
                        <h5 class="mt-2">Waktu Absensi Ditutup</h5>
                        <p class="mb-0">Silakan kembali lagi pada jam yang telah ditentukan.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
    <div class="col-md-8">
        {{-- KARTU UNTUK RIWAYAT ABSENSI --}}
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Riwayat Absensi Saya</h3>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Jam Absen</th>
                                <th>Status Kehadiran</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($riwayatAbsensi as $absensi)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($absensi->tanggal)->format('d M Y') }}</td>
                                {{-- Menampilkan jam absen dengan format Jam:Menit:Detik --}}
                                <td>{{ \Carbon\Carbon::parse($absensi->tanggal)->format('H:i:s') }}</td>
                                <td>
                                    <span class="badge badge-success">{{ $absensi->status }}</span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="text-center">Belum ada riwayat absensi.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer clearfix">
                {{ $riwayatAbsensi->links() }}
            </div>
        </div>
    </div>
</div>
@endsection