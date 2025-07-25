@extends('layouts.app')

@section('title', 'Dashboard Pembimbing Kominfo')

@section('content-header')
<div class="row mb-2">
    <div class="col-sm-6"><h1 class="m-0">Dashboard</h1></div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active">Dashboard Pembimbing Kominfo</li>
        </ol>
    </div>
</div>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-4 col-6">
        <!-- small box -->
        <div class="small-box bg-success">
            <div class="inner">
                <h3>{{ $jumlahPeserta }}</h3>
                <p>Peserta Bimbingan</p>
            </div>
            <div class="icon">
                <i class="fas fa-users"></i>
            </div>
            {{-- Link ini mengarah ke halaman daftar peserta yang berisi tabel --}}
            <a href="{{ route('pembimbing.kominfo.peserta.index') }}" class="small-box-footer">
                Lihat Daftar Lengkap <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Selamat Datang, {{ Auth::user()->name }}!</h3>
    </div>
    <div class="card-body">
        <p>Ini adalah halaman dashboard ringkasan Anda sebagai Pembimbing Kominfo.</p>
        <p>Gunakan menu navigasi di samping, seperti <strong>"Peserta Bimbingan"</strong>, untuk melihat daftar lengkap peserta Anda dan memantau aktivitas mereka secara rinci.</p>
    </div>
</div>
@endsection