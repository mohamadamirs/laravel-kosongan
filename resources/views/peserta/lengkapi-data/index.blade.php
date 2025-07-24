@extends('layouts.app')

@section('title', 'Lengkapi Data Diri')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-10">

            {{-- Tampilkan pesan sesuai status peserta --}}
            @if($peserta->status == 'registrasi')
                <div class="alert alert-info text-center">
                    <h4 class="alert-heading">Selamat Datang, {{ Auth::user()->name }}!</h4>
                    <p>Akun Anda telah berhasil dibuat. Sebelum melanjutkan, harap lengkapi data diri Anda di bawah ini dan unggah surat permohonan resmi dari sekolah/kampus Anda untuk proses verifikasi.</p>
                </div>
            @elseif($peserta->status == 'menunggu_persetujuan')
                <div class="alert alert-warning text-center">
                    <h4 class="alert-heading">Pengajuan Terkirim!</h4>
                    <p>Terima kasih telah melengkapi data. Permohonan Anda sedang dalam proses verifikasi oleh admin. Anda akan mendapatkan notifikasi jika status permohonan Anda berubah. Anda belum dapat mengakses fitur lain saat ini.</p>
                </div>
            @elseif($peserta->status == 'ditolak')
                 <div class="alert alert-danger text-center">
                    <h4 class="alert-heading">Permohonan Ditolak</h4>
                    <p>Mohon maaf, permohonan Anda sebelumnya telah ditolak. Silakan periksa kembali data atau surat permohonan Anda dan ajukan kembali jika diperlukan.</p>
                </div>
            @endif


            {{-- Tampilkan form hanya jika statusnya BUKAN 'menunggu_persetujuan' --}}
            @if($peserta->status != 'menunggu_persetujuan')
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Form Kelengkapan Data & Pengajuan Permohonan</h3>
                </div>
                <form action="{{ route('peserta.lengkapi-data.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nama">Nama Lengkap</label>
                                    <input type="text" class="form-control @error('nama') is-invalid @enderror" id="nama" name="nama" value="{{ old('nama', $peserta->nama) }}" required>
                                    @error('nama') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nisn">NISN / NIM</label>
                                    <input type="text" class="form-control @error('nisn') is-invalid @enderror" id="nisn" name="nisn" value="{{ old('nisn', $peserta->nisn) }}" required>
                                    @error('nisn') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                             <div class="col-md-6">
                                <div class="form-group">
                                    <label for="asal_sekolah">Asal Sekolah / Kampus</label>
                                    <input type="text" class="form-control @error('asal_sekolah') is-invalid @enderror" id="asal_sekolah" name="asal_sekolah" value="{{ old('asal_sekolah', $peserta->asal_sekolah) }}" required>
                                    @error('asal_sekolah') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                             <div class="col-md-3">
                                <div class="form-group">
                                    <label for="kelas">Kelas</label>
                                    <input type="text" class="form-control @error('kelas') is-invalid @enderror" id="kelas" name="kelas" value="{{ old('kelas', $peserta->kelas) }}" placeholder="Contoh: XII" required>
                                    @error('kelas') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="jurusan">Jurusan</label>
                                    <input type="text" class="form-control @error('jurusan') is-invalid @enderror" id="jurusan" name="jurusan" value="{{ old('jurusan', $peserta->jurusan) }}" placeholder="Contoh: RPL" required>
                                    @error('jurusan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="lahir">Tanggal Lahir</label>
                                    <input type="date" class="form-control @error('lahir') is-invalid @enderror" id="lahir" name="lahir" value="{{ old('lahir', $peserta->lahir) }}" required>
                                    @error('lahir') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="telepon">No. Telepon Aktif</label>
                                    <input type="tel" class="form-control @error('telepon') is-invalid @enderror" id="telepon" name="telepon" value="{{ old('telepon', $peserta->telepon) }}" required>
                                    @error('telepon') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                        </div>

                         <div class="form-group">
                            <label for="alamat">Alamat Lengkap</label>
                            <textarea class="form-control @error('alamat') is-invalid @enderror" id="alamat" name="alamat" rows="3" required>{{ old('alamat', $peserta->alamat) }}</textarea>
                            @error('alamat') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <hr>
                        <h5 class="mt-4 mb-3">Detail Periode Magang & Surat Permohonan</h5>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="mulai_magang">Tanggal Mulai Magang</label>
                                    <input type="date" class="form-control @error('mulai_magang') is-invalid @enderror" id="mulai_magang" name="mulai_magang" value="{{ old('mulai_magang', $peserta->mulai_magang) }}" required>
                                    @error('mulai_magang') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                             <div class="col-md-6">
                                <div class="form-group">
                                    <label for="selesai_magang">Tanggal Selesai Magang</label>
                                    <input type="date" class="form-control @error('selesai_magang') is-invalid @enderror" id="selesai_magang" name="selesai_magang" value="{{ old('selesai_magang', $peserta->selesai_magang) }}" required>
                                    @error('selesai_magang') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="file_surat">Unggah Surat Permohonan (PDF, maks 2MB)</label>
                            <input type="file" class="form-control-file @error('file_surat') is-invalid @enderror" id="file_surat" name="file_surat" required accept=".pdf">
                            @error('file_surat') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary btn-lg btn-block">Ajukan Permohonan Sekarang</button>
                    </div>
                </form>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection