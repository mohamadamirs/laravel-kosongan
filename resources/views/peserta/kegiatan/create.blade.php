@extends('layouts.app')

@section('title', 'Tambah Kegiatan Harian')

@section('content-header')
<div class="row mb-2">
    <div class="col-sm-6"><h1 class="m-0">Tambah Kegiatan Harian</h1></div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('peserta.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('peserta.kegiatan.index') }}">Kegiatan Harian</a></li>
            <li class="breadcrumb-item active">Tambah</li>
        </ol>
    </div>
</div>
@endsection

@section('content')
<div class="card card-primary">
    <div class="card-header"><h3 class="card-title">Form Tambah Kegiatan</h3></div>
    <form action="{{ route('peserta.kegiatan.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="tanggal">Tanggal Kegiatan</label>
                        <input type="date" class="form-control @error('tanggal') is-invalid @enderror" id="tanggal" name="tanggal" value="{{ old('tanggal', date('Y-m-d')) }}" required>
                        @error('tanggal') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="judul_kegiatan">Judul Kegiatan</label>
                        <input type="text" class="form-control @error('judul_kegiatan') is-invalid @enderror" id="judul_kegiatan" name="judul_kegiatan" value="{{ old('judul_kegiatan') }}" required>
                        @error('judul_kegiatan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="deskripsi">Deskripsi</label>
                <textarea class="form-control @error('deskripsi') is-invalid @enderror" id="deskripsi" name="deskripsi" rows="5" required>{{ old('deskripsi') }}</textarea>
                @error('deskripsi') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label for="foto">Foto Dokumentasi (Opsional)</label>
                <input type="file" class="form-control-file @error('foto') is-invalid @enderror" id="foto" name="foto">
                @error('foto') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                <small class="form-text text-muted">Maksimal 2MB. Tipe: JPG, PNG, GIF.</small>
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary">Tambah Kegiatan</button>
            <a href="{{ route('peserta.kegiatan.index') }}" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>
@endsection