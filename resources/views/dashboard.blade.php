@extends('layouts.app')

{{-- Mengatur judul halaman --}}
@section('title', 'Dashboard')

{{-- styling --}}
@push('styles')
<style>
  
</style>
@endpush

{{-- Konten untuk bagian header halaman --}}
@section('content-header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h1 class="m-0">Dashboard</h1>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active">Dashboard</li>
        </ol>
    </div>
  </div>
@endsection

{{-- Konten utama halaman --}}
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h5 class="m-0">Konten Unggulan</h5>
            </div>
            <div class="card-body">
                <p class="card-text">Selamat datang di halaman dashboard Anda.</p>
                <button id="showAlertButton" class="btn btn-primary">Tampilkan SweetAlert</button>
            </div>
        </div>
    </div>

</div>
@endsection

{{-- Script khusus untuk halaman ini --}}
@push('scripts')
<script>
    // Pastikan DOM sudah siap
    $(function(){
        $('#showAlertButton').on('click', function(){
            Swal.fire({
                title: 'Halo!',
                text: 'Ini adalah contoh SweetAlert2 yang berjalan di halaman ini.',
                icon: 'success',
                confirmButtonText: 'Keren!'
            });
        });
    });
</script>
@endpush