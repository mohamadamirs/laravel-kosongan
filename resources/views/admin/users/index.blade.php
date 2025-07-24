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
                            <th style="width: 10px">ID</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Peran (Role)</th>
                            <th style="width: 120px">Aksi</th>
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
                                        $roleClass = '';
                                        switch ($user->role) {
                                            case 'admin':
                                                $roleClass = 'badge-danger';
                                                break;
                                            case 'peserta':
                                                $roleClass = 'badge-primary';
                                                break;
                                            case 'pembimbing_instansi':
                                                $roleClass = 'badge-info';
                                                break;
                                            case 'pembimbing_kominfo':
                                                $roleClass = 'badge-success';
                                                break;
                                            default:
                                                $roleClass = 'badge-secondary';
                                        }
                                    @endphp
                                    <span
                                        class="badge {{ $roleClass }}">{{ ucfirst(str_replace('_', ' ', $user->role)) }}</span>
                                </td>
                                <td>
                                    @if ($user->role == 'peserta')
                                        <a href="{{ route('admin.users.assign.form', $user->id) }}"
                                            class="btn btn-info btn-sm" title="Tugaskan Pembimbing">
                                            <i class="fas fa-chalkboard-teacher"></i>
                                        </a>
                                    @endif
                                    <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-warning btn-sm"
                                        title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form id="delete-form-{{ $user->id }}"
                                        action="{{ route('admin.users.destroy', $user->id) }}" method="POST"
                                        class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-danger btn-sm btn-delete"
                                            data-form-id="delete-form-{{ $user->id }}" title="Hapus">
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
@endsection

@push('scripts')
    <script>
        document.querySelectorAll('.btn-delete').forEach(button => {
            button.addEventListener('click', function(event) {
                event.preventDefault();
                const formId = this.getAttribute('data-form-id');
                const form = document.getElementById(formId);

                Swal.fire({
                    // position:center;
                    title: 'Anda yakin?',
                    text: "Data pengguna ini akan dihapus secara permanen!",
                    icon: 'warning',
                    showCancelButton: true,
                    customClass: {
                        confirmButton: 'btn btn-danger m-2', // Tombol konfirmasi menjadi merah
                        cancelButton: 'btn btn-secondary' // Tombol batal menjadi abu-abu
                    },
                    buttonsStyling: false,
                    // confirmButtonColor: '#d63030',
                    // cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal'
                    // reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                })
            });
        });
    </script>
@endpush
