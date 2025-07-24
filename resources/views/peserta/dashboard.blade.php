@extends('layouts.app')

@section('title', 'Dashboard Peserta')

@section('content-header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0">Dashboard</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active">Dashboard Peserta</li>
            </ol>
        </div>
    </div>
@endsection

@section('content')
    {{-- Menampilkan pesan peringatan dari middleware --}}
    @if (session('warning'))
        <div class="alert alert-warning alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h5><i class="icon fas fa-exclamation-triangle"></i> Perhatian!</h5>
            {{ session('warning') }}
        </div>
    @endif

    <div class="row">
        {{-- Kartu Informasi Pembimbing Instansi --}}
        <div class="col-md-6">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h5 class="card-title m-0"><i class="fas fa-user-tie"></i> Pembimbing Instansi</h5>
                </div>
                <div class="card-body">
                    @if ($peserta->pembimbingInstansi)
                        <h6 class="card-title">
                            <b>{{ $peserta->pembimbingInstansi->user->name ?? 'Nama Tidak Tersedia' }}</b></h6>
                        <p class="card-text">
                            Berikut adalah detail pembimbing dari sekolah/kampus yang akan memonitor Anda.
                        </p>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item"><b>Bidang:</b> {{ $peserta->pembimbingInstansi->bidang }}</li>
                            <li class="list-group-item"><b>Telepon:</b> {{ $peserta->pembimbingInstansi->telepon }}</li>
                        </ul>
                    @else
                        <div class="text-center text-muted py-3">
                            <p class="mb-0">Belum Ditentukan</p>
                            <small>Admin sedang dalam proses menugaskan pembimbing untuk Anda.</small>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Kartu Informasi Pembimbing Kominfo --}}
        <div class="col-md-6">
            <div class="card card-success card-outline">
                <div class="card-header">
                    <h5 class="card-title m-0"><i class="fas fa-user-shield"></i> Pembimbing Kominfo (Lapangan)</h5>
                </div>
                <div class="card-body">
                    @if ($peserta->pembimbingKominfo)
                        <h6 class="card-title"><b>{{ $peserta->pembimbingKominfo->user->name ?? 'Nama Tidak Tersedia' }}</b>
                        </h6>
                        <p class="card-text">
                            Berikut adalah detail pembimbing lapangan yang akan mendampingi Anda selama PKL.
                        </p>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item"><b>Bidang:</b> {{ $peserta->pembimbingKominfo->bidang }}</li>
                            <li class="list-group-item"><b>Telepon:</b> {{ $peserta->pembimbingKominfo->telepon }}</li>
                        </ul>
                    @else
                        <div class="text-center text-muted py-3">
                            <p class="mb-0">Belum Ditentukan</p>
                            <small>Admin sedang dalam proses menugaskan pembimbing untuk Anda.</small>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Pesan Pemberitahuan Jika Terkunci --}}
    @if (!$peserta->pembimbing_instansi_id || !$peserta->pembimbing_kominfo_id)
        <div class="callout callout-info mt-3">
            <h5><i class="fas fa-info-circle"></i> Menunggu Penempatan</h5>
            <p>Menu navigasi seperti "Kegiatan Harian" dan lainnya akan aktif setelah Admin selesai menugaskan kedua
                pembimbing untuk Anda. Silakan cek kembali nanti.</p>
        </div>
    @endif
@endsection
