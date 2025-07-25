@extends('layouts.app')

@section('title', 'Ajukan Izin / Cuti')

@section('content-header')
<div class="row mb-2">
    <div class="col-sm-6"><h1 class="m-0">Ajukan Izin / Cuti</h1></div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('peserta.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('peserta.izin-cuti.index') }}">Izin / Cuti</a></li>
            <li class="breadcrumb-item active">Buat Pengajuan</li>
        </ol>
    </div>
</div>
@endsection

@section('content')
<div class="card card-primary">
    <div class="card-header"><h3 class="card-title">Form Pengajuan Izin / Cuti</h3></div>
    <form action="{{ route('peserta.izin-cuti.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="card-body">
            <div class="form-group">
                <label for="tanggal">Tanggal Izin</label>
                <input type="date" class="form-control @error('tanggal') is-invalid @enderror" id="tanggal" name="tanggal" value="{{ old('tanggal') }}" required>
                @error('tanggal') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label for="keterangan">Keterangan / Alasan</label>
                <textarea class="form-control @error('keterangan') is-invalid @enderror" id="keterangan" name="keterangan" rows="4" placeholder="Contoh: Sakit (Surat dokter terlampir)" required>{{ old('keterangan') }}</textarea>
                @error('keterangan') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label for="bukti_foto">Unggah Bukti (Wajib)</label>
                <input type="file" class="form-control-file @error('bukti_foto') is-invalid @enderror" id="bukti_foto" name="bukti_foto" required>
                @error('bukti_foto') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                <small class="form-text text-muted">Unggah foto surat dokter atau bukti pendukung lainnya. Maks 2MB.</small>
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary">Kirim Pengajuan</button>
            <a href="{{ route('peserta.izin-cuti.index') }}" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>
@endsection