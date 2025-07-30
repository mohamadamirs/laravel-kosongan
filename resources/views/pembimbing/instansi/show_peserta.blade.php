@extends('layouts.app')

@section('title', 'Detail Peserta: ' . $peserta->nama)

@section('content-header')
<div class="row mb-2">
    <div class="col-sm-6"><h1 class="m-0">Detail Aktivitas Peserta</h1></div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('pembimbing.instansi.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('pembimbing.instansi.peserta.index') }}">Peserta Bimbingan</a></li>
            <li class="breadcrumb-item active">Detail Peserta</li>
        </ol>
    </div>
</div>
@endsection

@section('content')
<div class="row">
    {{-- KARTU PROFIL PESERTA DENGAN FOTO --}}
    <div class="col-md-4">
        <div class="card card-primary card-outline">
            <div class="card-body box-profile">
                <div class="text-center brnd">
                    @php
                        $defaultFoto = 'https://adminlte.io/themes/v3/dist/img/user2-160x160.jpg';
                        $fotoUrl = ($peserta->gambar && $peserta->gambar !== 'default.png') ? asset('storage/peserta/fotos/' . $peserta->gambar) : $defaultFoto;
                    @endphp
                    <img class="profile-user-img img-fluid img-circle" src="{{ $fotoUrl }}" alt="Foto profil {{ $peserta->nama }}" style="width:120px; height:120px; object-fit:cover;">
                </div>
                <h3 class="profile-username text-center">{{ $peserta->nama }}</h3>
                <p class="text-muted text-center">{{ $peserta->jurusan }}</p>
                <ul class="list-group list-group-unbordered mb-3">
                    <li class="list-group-item"><b>NISN/NIM</b> <a class="float-right">{{ $peserta->nisn }}</a></li>
                    <li class="list-group-item"><b>Asal Sekolah</b> <a class="float-right">{{ $peserta->asal_sekolah }}</a></li>
                    <li class="list-group-item"><b>Telepon</b> <a class="float-right">{{ $peserta->telepon }}</a></li>
                    <li class="list-group-item"><b>Periode Magang</b> <a class="text-center float-right mt-2">{{ \Carbon\Carbon::parse($peserta->mulai_magang)->format('d M Y') }} - {{ \Carbon\Carbon::parse($peserta->selesai_magang)->format('d M Y') }}</a></li>
                </ul>
            </div>
        </div>
    </div>

    {{-- TABEL AKTIVITAS PESERTA --}}
    <div class="col-md-8">
        <div class="card">
            <div class="card-header p-2">
                <ul class="nav nav-pills">
                    <li class="nav-item"><a class="nav-link active" href="#kegiatan" data-toggle="tab">Kegiatan Harian</a></li>
                    <li class="nav-item"><a class="nav-link" href="#absensi" data-toggle="tab">Riwayat Absensi</a></li>
                    <li class="nav-item"><a class="nav-link" href="#izin" data-toggle="tab">Riwayat Izin Cuti</a></li>
                </ul>
            </div>
            <div class="card-body">
                <div class="tab-content">
                    {{-- TAB KEGIATAN --}}
                    <div class="active tab-pane" id="kegiatan">
                        @forelse($kegiatan as $item)
                            <div class="post">
                                <div class="user-block">
                                    <span class="username ml-0">{{ $item->judul_kegiatan }}</span>
                                    <span class="description ml-0">Dilaporkan pada - {{ \Carbon\Carbon::parse($item->tanggal)->format('d F Y') }}</span>
                                </div>
                                <p>{{ $item->deskripsi }}</p>
                                @if($item->foto)
                                <p><a href="{{ asset('storage/kegiatan/' . $item->foto) }}" target="_blank" class="link-black text-sm"><i class="fas fa-paperclip mr-1"></i> Lihat Lampiran Foto</a></p>
                                @endif
                            </div>
                        @empty
                            <p class="text-center text-muted">Peserta belum melaporkan kegiatan harian.</p>
                        @endforelse
                        <div class="mt-3">{{ $kegiatan->appends(['page_kegiatan' => $kegiatan->currentPage()])->links() }}</div>
                    </div>
                    {{-- TAB ABSENSI --}}
                    <div class="tab-pane" id="absensi">
                        <ul class="list-group list-group-flush">
                        @forelse($absensi as $item)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                {{ \Carbon\Carbon::parse($item->tanggal)->format('l, d F Y') }}
                                <div>
                                    <span class="badge badge-success">Hadir</span>
                                    <small class="text-muted ml-2">({{ \Carbon\Carbon::parse($item->tanggal)->format('H:i') }})</small>
                                </div>
                            </li>
                        @empty
                            <li class="list-group-item text-center text-muted">Belum ada riwayat absensi.</li>
                        @endforelse
                        </ul>
                        <div class="mt-3">{{ $absensi->appends(['page_absensi' => $absensi->currentPage()])->links() }}</div>
                    </div>
                    {{-- TAB IZIN --}}
                    <div class="tab-pane" id="izin">
                         @forelse($izinCuti as $item)
                            <div class="post">
                                <p><strong>Tanggal Izin:</strong> {{ \Carbon\Carbon::parse($item->tanggal)->format('d F Y') }}</p>
                                <p><strong>Keterangan:</strong><br>{{ $item->keterangan }}</p>
                                @if($item->bukti_foto)
                                <p><a href="{{ asset('storage/izin_cuti/' . $item->bukti_foto) }}" target="_blank" class="link-black text-sm"><i class="fas fa-paperclip mr-1"></i> Lihat Bukti Izin</a></p>
                                @endif
                            </div>
                         @empty
                            <p class="text-center text-muted">Peserta tidak memiliki riwayat izin atau cuti.</p>
                        @endforelse
                        <div class="mt-3">{{ $izinCuti->appends(['page_izin_cuti' => $izinCuti->currentPage()])->links() }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection