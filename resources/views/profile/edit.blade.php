@extends('layouts.app')

@section('title', 'Edit Profil Saya')

@section('content-header')
<div class="row mb-2">
    <div class="col-sm-6"><h1 class="m-0">Profil Saya</h1></div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('profile.show') }}">Profil Saya</a></li>
            <li class="breadcrumb-item active">Edit Profil</li>
        </ol>
    </div>
</div>
@endsection

@section('content')
<div class="card card-primary card-outline">
    <div class="card-header">
        <h3 class="card-title">Edit Detail Profil</h3>
    </div>
    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="card-body">

            {{-- =============================================== --}}
            {{--           BAGIAN UMUM (UNTUK SEMUA PERAN)         --}}
            {{-- =============================================== --}}
            <h5 class="mt-2 mb-3 text-muted">Informasi Akun</h5>
            <div class="form-group">
                <label for="name">Nama Lengkap</label>
                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $user->name) }}" required>
                @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="form-group">
                <label for="email">Alamat Email</label>
                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $user->email) }}" required>
                @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <hr>

            {{-- =============================================== --}}
            {{--        BAGIAN KHUSUS UNTUK PERAN PEMBIMBING       --}}
            {{-- =============================================== --}}
            @if(in_array($user->role, ['pembimbing_instansi', 'pembimbing_kominfo']))
                @php 
                    $profile = $user->pembimbingInstansi ?? $user->pembimbingKominfo;
                    $defaultFoto = 'https://adminlte.io/themes/v3/dist/img/user2-160x160.jpg';
                    $fotoUrl = $profile->foto ? asset('storage/avatars/' . $profile->foto) : $defaultFoto;
                @endphp
                <h5 class="mt-4 mb-3 text-muted">Detail Profil Pembimbing</h5>
                <div class="text-center mb-4">
                    <img id="foto-preview" src="{{ $fotoUrl }}" class="profile-user-img img-fluid img-circle" alt="Foto Profil">
                </div>
                <div class="form-group">
                    <label for="foto">Ganti Foto Profil (Opsional)</label>
                    <div class="custom-file">
                        <input type="file" class="custom-file-input @error('foto') is-invalid @enderror" id="foto" name="foto" accept="image/*">
                        <label class="custom-file-label" for="foto">Pilih file gambar...</label>
                    </div>
                    @error('foto') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                </div>
                <div class="form-group">
                    <label for="telepon">No. Telepon</label>
                    <input type="tel" class="form-control @error('telepon') is-invalid @enderror" id="telepon" name="telepon" value="{{ old('telepon', $profile->telepon ?? '') }}" required>
                    @error('telepon') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                 <div class="form-group">
                    <label for="bidang">Bidang/Jabatan</label>
                    <input type="text" class="form-control @error('bidang') is-invalid @enderror" id="bidang" name="bidang" value="{{ old('bidang', $profile->bidang ?? '') }}" required>
                    @error('bidang') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <hr>
            @endif

            {{-- =============================================== --}}
            {{--         BAGIAN KHUSUS UNTUK PERAN PESERTA         --}}
            {{-- =============================================== --}}
            @if($user->role == 'peserta' && $user->peserta)
                @php 
                    $profile = $user->peserta;
                    $defaultFoto = 'https://adminlte.io/themes/v3/dist/img/user2-160x160.jpg';
                    $fotoUrl = ($profile->gambar && $profile->gambar !== 'default.png') ? asset('storage/peserta/fotos/' . $profile->gambar) : $defaultFoto;
                @endphp
                 <h5 class="mt-4 mb-3 text-muted">Detail Profil Peserta</h5>
                <div class="text-center mb-4">
                    <img id="foto-preview" src="{{ $fotoUrl }}" class="profile-user-img img-fluid img-circle" alt="Foto Profil">
                </div>
                <div class="form-group">
                    <label for="foto">Ganti Foto Profil (Opsional)</label>
                    <div class="custom-file">
                        <input type="file" class="custom-file-input @error('foto') is-invalid @enderror" id="foto" name="foto" accept="image/*">
                        <label class="custom-file-label" for="foto">Pilih file gambar...</label>
                    </div>
                    @error('foto') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                </div>
                 <div class="row">
                    <div class="col-md-6"><div class="form-group"><label for="nisn">NISN / NIM</label><input type="text" class="form-control @error('nisn') is-invalid @enderror" id="nisn" name="nisn" value="{{ old('nisn', $profile->nisn) }}" required>@error('nisn') <div class="invalid-feedback">{{ $message }}</div> @enderror</div></div>
                    <div class="col-md-6"><div class="form-group"><label for="asal_sekolah">Asal Sekolah / Kampus</label><input type="text" class="form-control @error('asal_sekolah') is-invalid @enderror" id="asal_sekolah" name="asal_sekolah" value="{{ old('asal_sekolah', $profile->asal_sekolah) }}" required>@error('asal_sekolah') <div class="invalid-feedback">{{ $message }}</div> @enderror</div></div>
                </div>
                 <div class="row">
                    <div class="col-md-6"><div class="form-group"><label for="kelas">Kelas</label><input type="text" class="form-control @error('kelas') is-invalid @enderror" id="kelas" name="kelas" value="{{ old('kelas', $profile->kelas) }}" required>@error('kelas') <div class="invalid-feedback">{{ $message }}</div> @enderror</div></div>
                    <div class="col-md-6"><div class="form-group"><label for="jurusan">Jurusan</label><input type="text" class="form-control @error('jurusan') is-invalid @enderror" id="jurusan" name="jurusan" value="{{ old('jurusan', $profile->jurusan) }}" required>@error('jurusan') <div class="invalid-feedback">{{ $message }}</div> @enderror</div></div>
                </div>
                <div class="row">
                    <div class="col-md-6"><div class="form-group"><label for="lahir">Tanggal Lahir</label><input type="date" class="form-control @error('lahir') is-invalid @enderror" id="lahir" name="lahir" value="{{ old('lahir', $profile->lahir) }}" required>@error('lahir') <div class="invalid-feedback">{{ $message }}</div> @enderror</div></div>
                    <div class="col-md-6"><div class="form-group"><label for="telepon">No. Telepon Aktif</label><input type="tel" class="form-control @error('telepon') is-invalid @enderror" id="telepon" name="telepon" value="{{ old('telepon', $profile->telepon) }}" required>@error('telepon') <div class="invalid-feedback">{{ $message }}</div> @enderror</div></div>
                </div>
                <div class="form-group"><label for="alamat">Alamat Lengkap</label><textarea class="form-control @error('alamat') is-invalid @enderror" id="alamat" name="alamat" rows="3" required>{{ old('alamat', $profile->alamat) }}</textarea>@error('alamat') <div class="invalid-feedback">{{ $message }}</div> @enderror</div>
                <hr>
            @endif

            {{-- =============================================== --}}
            {{--      BAGIAN UBAH PASSWORD (UNTUK SEMUA PERAN)     --}}
            {{-- =============================================== --}}
            <h5 class="mt-4 mb-3 text-muted">Ubah Password (Isi jika ingin mengubah)</h5>
            <div class="form-group"><label for="current_password">Password Saat Ini</label><input type="password" class="form-control @error('current_password') is-invalid @enderror" id="current_password" name="current_password">@error('current_password') <div class="invalid-feedback">{{ $message }}</div> @enderror</div>
            <div class="form-group"><label for="password">Password Baru</label><input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password">@error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror</div>
            <div class="form-group"><label for="password_confirmation">Konfirmasi Password Baru</label><input type="password" class="form-control" id="password_confirmation" name="password_confirmation"></div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary">Update Profil</button>
            <a href="{{ route('profile.show') }}" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>
@endsection

@push('scripts')
{{-- JAVASCRIPT UNTUK PRATINJAU GAMBAR --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    const fotoInput = document.getElementById('foto');
    const fotoPreview = document.getElementById('foto-preview');
    const fileLabel = document.querySelector('.custom-file-label[for="foto"]');

    if (fotoInput && fotoPreview && fileLabel) {
        fotoInput.addEventListener('change', function(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    fotoPreview.src = e.target.result;
                };
                reader.readAsDataURL(file);
                fileLabel.textContent = file.name;
            }
        });
    }
});
</script>
@endpush