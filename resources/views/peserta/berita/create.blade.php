@extends('layouts.app')

@section('title', 'Ajukan Berita Baru')

@section('content-header')
<div class="row mb-2">
    <div class="col-sm-6"><h1 class="m-0">Ajukan Berita</h1></div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('peserta.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Ajukan Berita</li>
        </ol>
    </div>
</div>
@endsection

@section('content')
<div class="card card-primary">
    <div class="card-header"><h3 class="card-title">Form Pengajuan Berita</h3></div>
    <form action="{{ route('peserta.berita.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="card-body">
            <div class="alert alert-info">
                <i class="icon fas fa-info-circle"></i>
                Pengajuan berita Anda akan ditinjau terlebih dahulu oleh Admin sebelum dapat dipublikasikan di halaman utama.
            </div>
            
            <div class="form-group">
                <label for="judul">Judul Berita</label>
                <input type="text" class="form-control @error('judul') is-invalid @enderror" id="judul" name="judul" value="{{ old('judul') }}" placeholder="Contoh: Kegiatan Kunjungan Industri ke Perusahaan X" required>
                @error('judul') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label for="paragraf">Isi Berita / Deskripsi Kegiatan</label>
                <textarea class="form-control @error('paragraf') is-invalid @enderror" id="paragraf" name="paragraf" rows="8" placeholder="Ceritakan detail kegiatan atau informasi yang ingin Anda bagikan..." required>{{ old('paragraf') }}</textarea>
                @error('paragraf') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label for="foto">Foto Pendukung (Opsional)</label>
                <input type="file" class="form-control-file @error('foto') is-invalid @enderror" id="foto" name="foto">
                @error('foto') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                <small class="form-text text-muted">Maksimal 2MB. Tipe: JPG, PNG, GIF.</small>
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary">Kirim Pengajuan</button>
            <a href="{{ route('peserta.dashboard') }}" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>
@endsection