@extends('layouts.app')

@section('title', 'Tugaskan Pembimbing')

@section('content-header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h1 class="m-0">Tugaskan Pembimbing</h1>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.users.index') }}">Pengguna</a></li>
            <li class="breadcrumb-item active">Tugaskan Pembimbing</li>
        </ol>
    </div>
</div>
@endsection

@section('content')
<div class="card card-primary">
    <div class="card-header">
        <h3 class="card-title">Form Penugasan untuk Peserta: <strong>{{ $user->name }}</strong></h3>
    </div>
    <form action="{{ route('admin.users.assign.update', $user->id) }}" method="POST">
        @csrf
        @method('PATCH')
        <div class="card-body">
            <div class="form-group">
                <label for="pembimbing_instansi_id">Pilih Pembimbing Instansi</label>
                <select class="form-control @error('pembimbing_instansi_id') is-invalid @enderror" id="pembimbing_instansi_id" name="pembimbing_instansi_id" required>
                    <option value="">-- Silakan Pilih --</option>
                    @foreach($pembimbingInstansi as $pembimbing)
                        {{-- Kita ambil data pembimbing yang sudah ditugaskan dari $user->peserta->... --}}
                        <option value="{{ $pembimbing->id }}" {{ $user->peserta->pembimbing_instansi_id == $pembimbing->id ? 'selected' : '' }}>
                            {{ $pembimbing->user->name ?? 'ID: ' . $pembimbing->id }}
                        </option>
                    @endforeach
                </select>
                @error('pembimbing_instansi_id')
                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                @enderror
            </div>

            <div class="form-group">
                <label for="pembimbing_kominfo_id">Pilih Pembimbing Kominfo (Lapangan)</label>
                <select class="form-control @error('pembimbing_kominfo_id') is-invalid @enderror" id="pembimbing_kominfo_id" name="pembimbing_kominfo_id" required>
                    <option value="">-- Silakan Pilih --</option>
                    @foreach($pembimbingKominfo as $pembimbing)
                        <option value="{{ $pembimbing->id }}" {{ $user->peserta->pembimbing_kominfo_id == $pembimbing->id ? 'selected' : '' }}>
                            {{ $pembimbing->user->name ?? 'ID: ' . $pembimbing->id }}
                        </option>
                    @endforeach
                </select>
                @error('pembimbing_kominfo_id')
                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                @enderror
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary">Simpan Penugasan</button>
            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>
@endsection