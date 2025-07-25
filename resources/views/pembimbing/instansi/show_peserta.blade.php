@extends('layouts.app')

@section('title', 'Detail Peserta: ' . $peserta->nama)

@section('content-header')
<div class="row mb-2">
    <div class="col-sm-6"><h1 class="m-0">Detail Aktivitas Peserta</h1></div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('pembimbing.instansi.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Detail Peserta</li>
        </ol>
    </div>
</div>
@endsection

@section('content')
<div class="row">
    {{-- PROFIL PESERTA --}}
    <div class="col-md-4">
        <div class="card card-primary card-outline">
            <div class="card-body box-profile">
                <h3 class="profile-username text-center">{{ $peserta->nama }}</h3>
                <p class="text-muted text-center">{{ $peserta->jurusan }}</p>
                <ul class="list-group list-group-unbordered mb-3">
                    <li class="list-group-item"><b>NISN/NIM</b> <a class="float-right">{{ $peserta->nisn }}</a></li>
                    <li class="list-group-item"><b>Asal Sekolah</b> <a class="float-right">{{ $peserta->asal_sekolah }}</a></li>
                    <li class="list-group-item"><b>Telepon</b> <a class="float-right">{{ $peserta->telepon }}</a></li>
                    <li class="list-group-item"><b>Periode</b> <a class="float-right">{{ \Carbon\Carbon::parse($peserta->mulai_magang)->format('d M Y') }} - {{ \Carbon\Carbon::parse($peserta->selesai_magang)->format('d M Y') }}</a></li>
                </ul>
            </div>
        </div>
    </div>

    {{-- AKTIVITAS --}}
    <div class="col-md-8">
        <div class="card">
            <div class="card-header p-2">
                <ul class="nav nav-pills">
                    <li class="nav-item"><a class="nav-link active" href="#kegiatan" data-toggle="tab">Kegiatan Harian</a></li>
                    <li class="nav-item"><a class="nav-link" href="#absensi" data-toggle="tab">Absensi</a></li>
                    <li class="nav-item"><a class="nav-link" href="#izin" data-toggle="tab">Izin Cuti</a></li>
                </ul>
            </div>
            <div class="card-body">
                <div class="tab-content">
                    {{-- TAB KEGIATAN --}}
                    <div class="active tab-pane" id="kegiatan">
                        @forelse($peserta->kegiatan as $kegiatan)
                            <div class="post">
                                <div class="user-block">
                                    <span class="username ml-0">{{ $kegiatan->judul_kegiatan }}</span>
                                    <span class="description ml-0">{{ \Carbon\Carbon::parse($kegiatan->tanggal)->format('d F Y') }}</span>
                                </div>
                                <p>{{ $kegiatan->deskripsi }}</p>
                                @if($kegiatan->foto)
                                <p><a href="{{ asset('storage/kegiatan/' . $kegiatan->foto) }}" target="_blank">Lihat lampiran foto</a></p>
                                @endif
                            </div>
                        @empty
                            <p class="text-center text-muted">Peserta belum menambahkan kegiatan.</p>
                        @endforelse
                    </div>
                    {{-- TAB ABSENSI --}}
                    <div class="tab-pane" id="absensi">
                        <ul class="list-group">
                        @forelse($peserta->absensi as $absensi)
                            <li class="list-group-item">{{ \Carbon\Carbon::parse($absensi->tanggal)->format('d F Y') }} <span class="badge badge-success float-right">Hadir</span></li>
                        @empty
                            <li class="list-group-item text-center text-muted">Belum ada riwayat absensi.</li>
                        @endforelse
                        </ul>
                    </div>
                    {{-- TAB IZIN --}}
                    <div class="tab-pane" id="izin">
                         @forelse($peserta->izinCuti as $izin)
                            <div class="post">
                                <span class="description float-right">{{ \Carbon\Carbon::parse($izin->tanggal)->format('d F Y') }}</span>
                                <p><strong>Keterangan:</strong> {{ $izin->keterangan }}</p>
                                @if($izin->bukti_foto)
                                <p><a href="{{ asset('storage/izin_cuti/' . $izin->bukti_foto) }}" target="_blank">Lihat bukti</a></p>
                                @endif
                            </div>
                        @empty
                            <p class="text-center text-muted">Peserta tidak memiliki riwayat izin/cuti.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection