@extends('layouts.app')

@section('title', 'Tambah Ruangan')

@section('content-header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h1 class="m-0">Tambah Ruangan Baru</h1>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.ruangan.index') }}">Ruangan</a></li>
            <li class="breadcrumb-item active">Tambah</li>
        </ol>
    </div>
</div>
@endsection

@section('content')
<div class="card card-primary">
    <div class="card-header">
        <h3 class="card-title">Form Tambah Ruangan</h3>
    </div>
    <form action="{{ route('admin.ruangan.store') }}" method="POST">
        @csrf
        <div class="card-body">
            <div class="form-group">
                <label for="bidang">Nama Bidang</label>
                <input type="text" class="form-control @error('bidang') is-invalid @enderror" id="bidang" name="bidang"
                       value="{{ old('bidang') }}" placeholder="Contoh: Jaringan Komputer" required>
                @error('bidang')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>
            <div class="form-group">
                <label for="tempat">Tempat / Lokasi</label>
                <input type="text" class="form-control @error('tempat') is-invalid @enderror" id="tempat" name="tempat"
                       value="{{ old('tempat') }}" placeholder="Contoh: Gedung A, Lantai 2" required>
                @error('tempat')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>
            <div class="form-group">
                <label for="maksimal">Kapasitas Maksimal (Orang)</label>
                <input type="number" class="form-control @error('maksimal') is-invalid @enderror" id="maksimal" name="maksimal"
                       value="{{ old('maksimal') }}" placeholder="Contoh: 5" required min="1">
                @error('maksimal')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="{{ route('admin.ruangan.index') }}" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>
@endsection