@extends('layouts.app')

@section('title', 'Manajemen Surat Masuk')

@section('content-header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h1 class="m-0">Manajemen Surat Masuk</h1>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Surat Masuk</li>
        </ol>
    </div>
</div>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Daftar Pengajuan Permohonan PKL</h3>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover">
                <thead>
                    <tr>
                        <th style="width: 10px">ID</th>
                        <th>Nama Peserta</th>
                        <th>Asal Sekolah/Kampus</th>
                        <th>Tanggal Pengajuan</th>
                        <th>File Surat</th>
                        <th>Status</th>
                        <th style="width: 200px">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($suratMasuk as $surat)
                    <tr>
                        <td>{{ $suratMasuk->firstItem() + $loop->index }}</td>
                        <td>{{ $surat->peserta->nama ?? 'Peserta tidak ditemukan' }}</td>
                        <td>{{ $surat->peserta->asal_sekolah ?? 'N/A' }}</td>
                        <td>{{ $surat->created_at->format('d F Y') }}</td>
                        <td>
                            @if($surat->file)
                                <a href="{{ asset('storage/surat_masuk/' . $surat->file) }}" target="_blank" class="btn btn-primary btn-sm">
                                    <i class="fas fa-download"></i> Unduh
                                </a>
                            @else
                                <span class="text-muted">Tidak ada file</span>
                            @endif
                        </td>
                        <td>
                            @php
                                $statusClass = 'badge-secondary';
                                switch ($surat->status) {
                                    case 'diterima': $statusClass = 'badge-success'; break;
                                    case 'ditolak': $statusClass = 'badge-danger'; break;
                                    case 'menunggu_verifikasi': $statusClass = 'badge-warning'; break;
                                }
                            @endphp
                            <span class="badge {{ $statusClass }}">{{ ucfirst(str_replace('_', ' ', $surat->status)) }}</span>
                        </td>
                        <td>
                            {{-- Tombol aksi hanya muncul jika status masih menunggu --}}
                            @if($surat->status == 'menunggu_verifikasi')
                                <form action="{{ route('admin.surat-masuk.updateStatus', $surat->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="diterima">
                                    <button type="submit" class="btn btn-success btn-sm" title="Terima Permohonan">
                                        <i class="fas fa-check"></i> Terima
                                    </button>
                                </form>
                                <form action="{{ route('admin.surat-masuk.updateStatus', $surat->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="ditolak">
                                    <button type="submit" class="btn btn-danger btn-sm" title="Tolak Permohonan">
                                        <i class="fas fa-times"></i> Tolak
                                    </button>
                                </form>
                            @else
                                <span class="text-muted">Sudah diproses</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center">Tidak ada surat masuk atau pengajuan baru.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer clearfix">
        {{ $suratMasuk->links() }}
    </div>
</div>
@endsection