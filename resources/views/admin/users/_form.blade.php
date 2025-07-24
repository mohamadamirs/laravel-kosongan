@csrf

<div class="card-body">
    {{-- Field Nama Lengkap --}}
    <div class="form-group">
        <label for="name">Nama Lengkap</label>
        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name"
               value="{{ old('name', $user->name ?? '') }}" required>
        @error('name')
        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
        @enderror
    </div>

    {{-- Field Alamat Email --}}
    <div class="form-group">
        <label for="email">Alamat Email</label>
        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email"
               value="{{ old('email', $user->email ?? '') }}" required>
        @error('email')
        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
        @enderror
    </div>

    {{-- Field Peran (Role) - Ini adalah pemicu untuk field dinamis --}}
    <div class="form-group">
        <label for="role">Peran (Role)</label>
        <select class="form-control @error('role') is-invalid @enderror" id="role-select" name="role" required>
            <option value="">-- Pilih Peran --</option>
            <option value="admin" {{ (old('role', $user->role ?? '') == 'admin') ? 'selected' : '' }}>Admin</option>
            <option value="peserta" {{ (old('role', $user->role ?? '') == 'peserta') ? 'selected' : '' }}>Peserta</option>
            <option value="pembimbing_instansi" {{ (old('role', $user->role ?? '') == 'pembimbing_instansi') ? 'selected' : '' }}>Pembimbing Instansi</option>
            <option value="pembimbing_kominfo" {{ (old('role', $user->role ?? '') == 'pembimbing_kominfo') ? 'selected' : '' }}>Pembimbing Kominfo</option>
        </select>
        @error('role')
        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
        @enderror
    </div>

    <!-- =================================================================== -->
    <!--            FIELD DINAMIS YANG HANYA MUNCUL UNTUK PESERTA              -->
    <!-- =================================================================== -->
    <div id="periode-magang-fields" style="display: none;"> {{-- Awalnya disembunyikan --}}
        <hr>
        <p class="text-muted"><strong>Khusus Peserta:</strong> Harap isi periode magang.</p>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="mulai_magang">Tanggal Mulai Magang</label>
                    {{-- Mengambil data dari relasi: $user->peserta->mulai_magang --}}
                    <input type="date" class="form-control @error('mulai_magang') is-invalid @enderror" id="mulai_magang" name="mulai_magang" value="{{ old('mulai_magang', $user->peserta->mulai_magang ?? '') }}">
                    @error('mulai_magang')
                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                    @enderror
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="selesai_magang">Tanggal Selesai Magang</label>
                    <input type="date" class="form-control @error('selesai_magang') is-invalid @enderror" id="selesai_magang" name="selesai_magang" value="{{ old('selesai_magang', $user->peserta->selesai_magang ?? '') }}">
                    @error('selesai_magang')
                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                    @enderror
                </div>
            </div>
        </div>
        <hr>
    </div>
    <!-- =================================================================== -->

    {{-- Field Password --}}
    <div class="form-group">
        <label for="password">Password</label>
        <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password"
               {{ isset($user) ? '' : 'required' }}>
        @if(isset($user))
        <small class="form-text text-muted">Kosongkan jika tidak ingin mengubah password.</small>
        @endif
        @error('password')
        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
        @enderror
    </div>

    {{-- Field Konfirmasi Password --}}
    <div class="form-group">
        <label for="password_confirmation">Konfirmasi Password</label>
        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation"
               {{ isset($user) ? '' : 'required' }}>
    </div>
</div>

<div class="card-footer">
    <button type="submit" class="btn btn-primary">{{ $submitButtonText ?? 'Simpan' }}</button>
    <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Batal</a>
</div>


@push('scripts')
{{-- Script untuk menampilkan/menyembunyikan field periode magang secara dinamis --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    const roleSelect = document.getElementById('role-select');
    const periodeMagangFields = document.getElementById('periode-magang-fields');
    const mulaiMagangInput = document.getElementById('mulai_magang');
    const selesaiMagangInput = document.getElementById('selesai_magang');

    function togglePeriodeFields() {
        if (roleSelect.value === 'peserta') {
            periodeMagangFields.style.display = 'block';
            // Jadikan field wajib diisi (required) jika peran peserta dipilih
            mulaiMagangInput.required = true;
            selesaiMagangInput.required = true;
        } else {
            periodeMagangFields.style.display = 'none';
            // Hapus kewajiban mengisi jika peran lain yang dipilih
            mulaiMagangInput.required = false;
            selesaiMagangInput.required = false;
        }
    }

    // Jalankan fungsi saat halaman pertama kali dimuat.
    // Ini penting untuk halaman 'edit' agar field langsung muncul jika user yang diedit adalah peserta.
    togglePeriodeFields();

    // Tambahkan event listener untuk memanggil fungsi setiap kali pilihan role berubah.
    roleSelect.addEventListener('change', togglePeriodeFields);
});
</script>
@endpush