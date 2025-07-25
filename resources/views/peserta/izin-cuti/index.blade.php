@extends('layouts.app')

@section('title', 'Riwayat Izin / Cuti')

@section('content-header')
<div class="row mb-2">
    <div class="col-sm-6"><h1 class="m-0">Izin / Cuti</h1></div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('peserta.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Izin / Cuti</li>
        </ol>
    </div>
</div>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Riwayat Pengajuan Saya</h3>
        <div class="card-tools">
            <a href="{{ route('peserta.izin-cuti.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Ajukan Izin / Cuti Baru
            </a>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover">
                <thead>
                    <tr>
                        <th>Tanggal Izin</th>
                        <th>Keterangan</th>
                        <th>Bukti</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($riwayatIzin as $izin)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($izin->tanggal)->format('d M Y') }}</td>
                        <td>{{ $izin->keterangan }}</td>
                        <td>
                            <a href="{{ asset('storage/izin_cuti/' . $izin->bukti_foto) }}" target="_blank" class="btn btn-secondary btn-xs">
                                <i class="fas fa-eye"></i> Lihat Bukti
                            </a>
                        </td>
                        <td>
                            {{-- Nantinya, kolom status ini bisa di-update oleh admin --}}
                            <span class="badge badge-warning">Diajukan</span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center">Anda belum pernah mengajukan izin atau cuti.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer clearfix">
        {{ $riwayatIzin->links() }}
    </div>
</div>
@endsection