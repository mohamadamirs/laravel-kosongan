@extends('layouts.app')

@section('title', 'Edit Berita')

@section('content-header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h1 class="m-0">Edit Berita</h1>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.berita.index') }}">Berita</a></li>
            <li class="breadcrumb-item active">Edit</li>
        </ol>
    </div>
</div>
@endsection

@section('content')
<div class="card card-warning">
    <div class="card-header">
        <h3 class="card-title">Form Edit Berita</h3>
    </div>
    <form action="{{ route('admin.berita.update', $berita->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="card-body">
            <div class="form-group">
                <label for="judul">Judul Berita</label>
                <input type="text" class="form-control @error('judul') is-invalid @enderror" id="judul" name="judul" value="{{ old('judul', $berita->judul) }}" required>
                @error('judul')
                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                @enderror
            </div>

            <div class="form-group">
                <label for="peserta_id">Terkait Peserta (Opsional)</label>
                <select class="form-control @error('peserta_id') is-invalid @enderror" id="peserta_id" name="peserta_id">
                    <option value="">-- Berita Umum --</option>
                    @foreach ($peserta as $p)
                        <option value="{{ $p->id }}" {{ old('peserta_id', $berita->peserta_id) == $p->id ? 'selected' : '' }}>
                            {{ $p->nama }}
                        </option>
                    @endforeach
                </select>
                @error('peserta_id')
                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                @enderror
            </div>

            <div class="form-group">
                <label for="paragraf">Isi Berita</label>
                <textarea class="form-control @error('paragraf') is-invalid @enderror" id="paragraf" name="paragraf" rows="8" required>{{ old('paragraf', $berita->paragraf) }}</textarea>
                @error('paragraf')
                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                @enderror
            </div>

            <div class="form-group">
                <label for="foto">Ganti Foto Sampul (Opsional)</label>
                @if($berita->foto)
                <div class="mb-2">
                    <p>Foto saat ini:</p>
                    <img src="{{ asset('storage/berita/' . $berita->foto) }}" alt="Foto saat ini" style="max-height: 150px;" class="img-fluid rounded">
                </div>
                @endif
                <input type="file" class="form-control-file @error('foto') is-invalid @enderror" id="foto" name="foto">
                @error('foto')
                <span class="invalid-feedback d-block" role="alert"><strong>{{ $message }}</strong></span>
                @enderror
                <small class="form-text text-muted">Kosongkan jika tidak ingin mengubah foto.</small>
            </div>
        </div>

        <div class="card-footer">
            <button type="submit" class="btn btn-primary">Update Berita</button>
            <a href="{{ route('admin.berita.index') }}" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>
@endsection