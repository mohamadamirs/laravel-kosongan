@extends('layouts.app')

@section('title', 'Dashboard Pembimbing')

@section('content-header')
<div class="row mb-2">
    <div class="col-sm-6"><h1 class="m-0">Dashboard</h1></div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active">Dashboard Pembimbing</li>
        </ol>
    </div>
</div>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-4 col-6">
        <!-- small box -->
        <div class="small-box bg-info">
            <div class="inner">
                {{-- Gunakan variabel $jumlahPeserta dari DashboardController --}}
                <h3>{{ $jumlahPeserta }}</h3>
                <p>Peserta Bimbingan</p>
            </div>
            <div class="icon">
                <i class="fas fa-users"></i>
            </div>
            {{-- Link ini mengarah ke halaman daftar peserta yang berisi tabel --}}
            <a href="{{ route('pembimbing.instansi.peserta.index') }}" class="small-box-footer">
                Lihat Daftar Peserta <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
    {{-- Anda bisa menambahkan info box lain di sini jika perlu --}}
</div>

<div class="callout callout-info">
    <h5>Selamat Datang, {{ Auth::user()->name }}!</h5>
    <p>Ini adalah halaman dashboard Anda. Gunakan menu di samping untuk melihat daftar peserta yang Anda bimbing dan memantau aktivitas mereka secara rinci.</p>
</div>
@endsection