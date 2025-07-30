@extends('layouts.app')

@section('title', 'Daftar Peserta Bimbingan')

@section('content-header')
<div class="row mb-2">
    <div class="col-sm-6"><h1 class="m-0">Peserta Bimbingan</h1></div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('pembimbing.lapangan.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Peserta Bimbingan</li>
        </ol>
    </div>
</div>
@endsection

@section('content')
<div class="card">
    <div class="card-header"><h3 class="card-title">Daftar Lengkap Peserta di Bawah Bimbingan Anda</h3></div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover">
                <thead>
                    <tr>
                        <th>Nama Peserta</th>
                        <th>NISN / NIM</th>
                        <th>Asal Sekolah / Kampus</th>
                        <th>Status Magang</th>
                        <th style="width:100px">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($pesertaBimbingan as $peserta)
                    <tr>
                        <td>{{ $peserta->nama }}</td>
                        <td>{{ $peserta->nisn }}</td>
                        <td>{{ $peserta->asal_sekolah }}</td>
                        <td>
                            <span class="badge {{ $peserta->status == 'aktif' ? 'badge-success' : 'badge-secondary' }}">
                                {{ ucfirst($peserta->status) }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('pembimbing.lapangan.peserta.show', $peserta->id) }}" class="btn btn-info btn-sm">
                                <i class="fas fa-eye"></i> Detail
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center">Saat ini Anda belum membimbing peserta manapun.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer clearfix">
        {{ $pesertaBimbingan->links() }}
    </div>
</div>
@endsection