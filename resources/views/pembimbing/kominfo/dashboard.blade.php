@extends('layouts.app')

@section('title', 'Dashboard Pembimbing Kominfo')

@section('content-header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h1 class="m-0">Dashboard Pembimbing Kominfo</h1>
    </div>
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
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h5 class="m-0">Selamat Datang, Pembimbing Kominfo!</h5>
            </div>
            <div class="card-body">
                <p class="card-text">Ini adalah dashboard khusus untuk pembimbing Kominfo. Anda dapat memantau peserta bimbingan dan aktivitas mereka di sini.</p>
            </div>
        </div>
    </div>
</div>
@endsection
