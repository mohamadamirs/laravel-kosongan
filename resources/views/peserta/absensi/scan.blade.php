@extends('layouts.app')

@section('title', 'Scan QR Code Absensi')

@section('content-header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h1 class="m-0">Scan QR Code Absensi</h1>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('peserta.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('peserta.absensi.index') }}">Absensi</a></li>
            <li class="breadcrumb-item active">Scan</li>
        </ol>
    </div>
</div>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8 col-lg-6">
        <div class="card">
            <div class="card-header text-center">
                <h3 class="card-title">Arahkan Kamera ke QR Code</h3>
            </div>
            <div class="card-body">
                {{-- Wadah untuk menampilkan kamera --}}
                <div id="qr-reader" style="width: 100%;"></div>
                {{-- Wadah untuk menampilkan hasil (opsional, untuk debugging) --}}
                <div id="qr-reader-results" class="mt-3 text-center"></div>
            </div>
            <div class="card-footer text-center">
                <small class="text-muted">Browser akan meminta izin untuk menggunakan kamera Anda.</small>
            </div>
        </div>
    </div>
</div>

{{-- PENTING: Peringatan jika koneksi tidak aman (HTTPS) --}}
<script>
    if (location.protocol !== 'https:') {
        alert('Peringatan: Akses kamera memerlukan koneksi HTTPS yang aman. Fitur scan mungkin tidak berfungsi.');
    }
</script>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const resultContainer = document.getElementById('qr-reader-results');
    let lastResult, countResults = 0;

    // Fungsi yang akan dijalankan ketika QR code berhasil dipindai
    function onScanSuccess(decodedText, decodedResult) {
        // `decodedText` berisi URL dari QR code
        
        // Mencegah pemindaian berulang-ulang dari QR code yang sama
        if (decodedText !== lastResult) {
            lastResult = decodedText;
            
            // Tampilkan feedback dan redirect
            resultContainer.innerHTML = `<div class="alert alert-success">QR Code terdeteksi! Mengarahkan...</div>`;
            
            // Redirect browser ke URL yang ada di dalam QR code
            window.location.href = decodedText;
        }
    }

    // Buat instance scanner baru
    var html5QrcodeScanner = new Html5QrcodeScanner(
        "qr-reader", // ID dari div wadah kamera
        { fps: 10, qrbox: 250 } // Konfigurasi: 10 frame per detik, kotak scan 250x250px
    );

    // Mulai proses scanning
    html5QrcodeScanner.render(onScanSuccess);
});
</script>
@endpush