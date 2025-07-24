@extends('layouts.app')

@section('title', 'Manajemen Ruangan')

@section('content-header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h1 class="m-0">Manajemen Ruangan</h1>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Ruangan</li>
        </ol>
    </div>
</div>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Daftar Ruangan</h3>
        <div class="card-tools">
            <a href="{{ route('admin.ruangan.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Tambah Ruangan
            </a>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover">
                <thead>
                    <tr>
                        <th style="width: 10px">ID</th>
                        <th>Nama Bidang</th>
                        <th>Tempat / Lokasi</th>
                        <th>Kapasitas Maksimal</th>
                        <th style="width: 120px">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    {{-- Menggunakan variabel $ruangan sesuai permintaan --}}
                    @forelse ($ruangan as $item)
                    <tr>
                        {{-- Menggunakan $ruangan->firstItem() untuk nomor urut paginasi --}}
                        <td>{{ $ruangan->firstItem() + $loop->index }}</td>
                        <td>{{ $item->bidang }}</td>
                        <td>{{ $item->tempat }}</td>
                        <td>{{ $item->maksimal }} orang</td>
                        <td>
                            <a href="{{ route('admin.ruangan.edit', $item->id) }}" class="btn btn-warning btn-sm" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form id="delete-form-{{ $item->id }}" action="{{ route('admin.ruangan.destroy', $item->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="btn btn-danger btn-sm btn-delete" data-form-id="delete-form-{{ $item->id }}" title="Hapus">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center">Tidak ada data ruangan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer clearfix">
        {{-- Menampilkan link paginasi --}}
        {{ $ruangan->links() }}
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
            const form = document.getElementById(formId);

            Swal.fire({
                title: 'Anda yakin?',
                text: "Data ruangan ini akan dihapus!",
                icon: 'warning',
                showCancelButton: true,
                customClass: {
                    confirmButton: 'btn btn-danger mx-2',
                    cancelButton: 'btn btn-secondary'
                },
                buttonsStyling: false,
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal',
                reverseButtons: false
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
</script>
@endpush