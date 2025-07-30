@extends('layouts.app')

@section('title', 'Manajemen Pengguna')

@section('content-header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h1 class="m-0">Manajemen Pengguna</h1>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Pengguna</li>
        </ol>
    </div>
</div>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Daftar Pengguna</h3>
        <div class="card-tools">
            <a href="{{ route('admin.users.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Tambah Pengguna
            </a>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover">
                <thead>
                    <tr>
                        <th style="width: 10px">#</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Peran (Role)</th>
                        <th style="width: 180px">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($users as $user)
                    <tr>
                        <td>{{ $users->firstItem() + $loop->index }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>
                            @php
                                $roleClass = 'badge-secondary';
                                switch ($user->role) {
                                    case 'admin': $roleClass = 'badge-danger'; break;
                                    case 'peserta': $roleClass = 'badge-primary'; break;
                                    case 'pembimbing_instansi': $roleClass = 'badge-info'; break;
                                    case 'pembimbing_kominfo': $roleClass = 'badge-success'; break;
                                }
                            @endphp
                            @php
                                $roleName = str_replace('_', ' ', $user->role);
                                if ($user->role == 'pembimbing_instansi') {
                                    $roleName = 'Pembimbing Guru/Dosen';
                                } elseif ($user->role == 'pembimbing_kominfo') {
                                    $roleName = 'Pembimbing Lapangan';
                                }
                            @endphp
                            <span class="badge {{ $roleClass }}">{{ ucfirst($roleName) }}</span>
                        </td>
                        <td>
                            {{-- Tampilkan tombol khusus hanya untuk peran 'peserta' --}}
                            @if($user->role == 'peserta')
                                {{-- Tombol untuk menugaskan pembimbing --}}
                                <a href="{{ route('admin.users.assign.form', $user->id) }}" class="btn btn-info btn-sm" title="Tugaskan Pembimbing">
                                    <i class="fas fa-chalkboard-teacher"></i>
                                </a>
                                {{-- Tombol untuk menampilkan QR Code Absensi --}}
                                <button type="button" class="btn btn-default btn-sm btn-generate-qr" data-toggle="modal" data-target="#qrModal" data-user-name="{{ $user->name }}" data-qr-url="{{ route('admin.users.qr', $user->id) }}" title="Buat QR Absensi">
                                    <i class="fas fa-qrcode"></i>
                                </button>
                            @endif

                            {{-- Tombol Edit (selalu ada) --}}
                            <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-warning btn-sm" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>

                            {{-- Tombol Hapus (selalu ada) --}}
                            <form id="delete-form-{{ $user->id }}" action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="btn btn-danger btn-sm btn-delete" data-form-id="delete-form-{{ $user->id }}" title="Hapus">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center">Tidak ada data pengguna.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer clearfix">
        {{ $users->links() }}
    </div>
</div>

<!-- ====================================================== -->
<!--       MODAL UNTUK MENAMPILKAN QR CODE ABSENSI            -->
<!-- ====================================================== -->
<div class="modal fade" id="qrModal" tabindex="-1" role="dialog" aria-labelledby="qrModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="qrModalLabel">QR Code Absensi untuk...</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body text-center" id="qr-code-container">
                {{-- Konten QR Code akan dimuat di sini oleh JavaScript --}}
                <div class="spinner-border text-primary" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
                <p class="mt-2 text-muted">Memuat QR Code...</p>
            </div>
            <div class="modal-footer">
                <p class="text-muted mr-auto"><i class="fas fa-info-circle"></i> QR Code ini hanya valid selama 1 menit.</p>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
{{-- Script SweetAlert untuk konfirmasi hapus --}}
<script>
    document.querySelectorAll('.btn-delete').forEach(button => {
        button.addEventListener('click', function (event) {
            event.preventDefault();
            const formId = this.getAttribute('data-form-id');
            Swal.fire({
                title: 'Anda yakin?',
                text: "Data pengguna ini dan semua data terkait akan dihapus permanen!",
                icon: 'warning',
                showCancelButton: true,
                customClass: { confirmButton: 'btn btn-danger', cancelButton: 'btn btn-secondary' },
                buttonsStyling: false,
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById(formId).submit();
                }
            });
        });
    });
</script>

{{-- Script untuk memuat QR Code ke dalam Modal --}}
<script>
    // Menggunakan jQuery karena sudah ada di AdminLTE
    $('#qrModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget); // Tombol yang memicu modal
        var userName = button.data('userName');
        var qrUrl = button.data('qrUrl');
        var modal = $(this);

        // Set judul modal
        modal.find('.modal-title').text('QR Code Absensi untuk ' + userName);

        // Tampilkan spinner saat memuat
        var spinner = '<div class="spinner-border text-primary" role="status"><span class="sr-only">Loading...</span></div><p class="mt-2 text-muted">Memuat QR Code...</p>';
        modal.find('#qr-code-container').html(spinner);
        
        // Buat elemen gambar baru dan muat QR code
        var img = new Image();
        img.src = qrUrl;
        img.alt = 'QR Code Absensi';
        
        // Setelah gambar selesai dimuat, ganti spinner dengan gambar
        img.onload = function() {
            modal.find('#qr-code-container').html(img);
        };
    });
</script>
@endpush