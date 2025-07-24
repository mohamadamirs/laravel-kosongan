@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('content-header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h1 class="m-0">Dashboard</h1>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
            <li class="breadcrumb-item active">Dashboard</li>
        </ol>
    </div>
</div>
@endsection

@section('content')
{{-- Baris Pertama: Info Box Utama --}}
<div class="row">
    <div class="col-lg-3 col-6">
        {{-- Info Box untuk Pendaftar Baru --}}
        <div class="small-box bg-warning">
            <div class="inner">
                <h3>{{ $jumlahPendaftarBaru }}</h3>
                <p>Pendaftar Baru</p>
            </div>
            <div class="icon">
                <i class="fas fa-user-plus"></i>
            </div>
            {{-- Mengarahkan ke halaman manajemen surat masuk untuk persetujuan --}}
            <a href="{{ route('admin.surat-masuk.index') }}" class="small-box-footer">
                Lihat Pengajuan <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        {{-- Info Box untuk Total Peserta --}}
        <div class="small-box bg-info">
            <div class="inner">
                <h3>{{ $jumlahPeserta }}</h3>
                <p>Total Akun Peserta</p>
            </div>
            <div class="icon">
                <i class="fas fa-users"></i>
            </div>
            {{-- PERBAIKAN: Mengarahkan ke halaman manajemen pengguna --}}
            <a href="{{ route('admin.users.index') }}" class="small-box-footer">
                Lihat Semua Pengguna <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        {{-- Info Box untuk Pembimbing Instansi --}}
        <div class="small-box bg-success">
            <div class="inner">
                <h3>{{ $jumlahPembimbingInstansi }}</h3>
                <p>Pembimbing Instansi</p>
            </div>
            <div class="icon">
                <i class="fas fa-user-tie"></i>
            </div>
            {{-- PERBAIKAN: Mengarahkan ke halaman manajemen pengguna --}}
            <a href="{{ route('admin.users.index') }}" class="small-box-footer">
                Lihat Semua Pengguna <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        {{-- Info Box untuk Pembimbing Kominfo --}}
        <div class="small-box bg-danger">
            <div class="inner">
                <h3>{{ $jumlahPembimbingKominfo }}</h3>
                <p>Pembimbing Kominfo</p>
            </div>
            <div class="icon">
                <i class="fas fa-user-shield"></i>
            </div>
            {{-- PERBAIKAN: Mengarahkan ke halaman manajemen pengguna --}}
            <a href="{{ route('admin.users.index') }}" class="small-box-footer">
                Lihat Semua Pengguna <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
</div>

{{-- Baris Kedua: Kartu Informasi Tambahan --}}
<div class="row">
    <div class="col-lg-8">
        {{-- Kartu untuk Berita Terbaru --}}
        <div class="card card-outline card-primary">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-newspaper mr-1"></i> 5 Berita Terbaru yang Diterbitkan</h3>
                <div class="card-tools">
                    <a href="{{ route('admin.berita.index') }}" class="btn btn-tool btn-sm">
                        <i class="fas fa-external-link-alt"></i> Lihat Semua
                    </a>
                </div>
            </div>
            <div class="card-body p-0">
                <ul class="list-group list-group-flush">
                    @forelse(\App\Models\Berita::where('status', 'diterbitkan')->latest()->take(5)->get() as $berita)
                        <li class="list-group-item">
                            <strong>{{ $berita->judul }}</strong>
                            <span class="float-right text-muted small">{{ $berita->created_at->diffForHumans() }}</span>
                        </li>
                    @empty
                        <li class="list-group-item text-muted text-center">Belum ada berita yang diterbitkan.</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        {{-- Kartu untuk Data Master --}}
        <div class="card card-outline card-info">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-database mr-1"></i> Data Master</h3>
            </div>
            <div class="card-body">
                <ul class="list-group list-group-unbordered mb-3">
                    <li class="list-group-item">
                        <b>Total Ruangan</b> <a href="{{ route('admin.ruangan.index') }}" class="float-right">{{ $jumlahRuangan }}</a>
                    </li>
                    <li class="list-group-item">
                        <b>Total Akun Pengguna</b> <a href="{{ route('admin.users.index') }}" class="float-right">{{ \App\Models\User::count() }}</a>
                    </li>
                    <li class="list-group-item">
                        <b>Total Berita</b> <a href="{{ route('admin.berita.index') }}" class="float-right">{{ \App\Models\Berita::count() }}</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection