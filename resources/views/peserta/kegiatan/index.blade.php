@extends('layouts.app')

@section('title', 'Kegiatan Harian')

@section('content-header')
<div class="row mb-2">
    <div class="col-sm-6"><h1 class="m-0">Kegiatan Harian</h1></div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('peserta.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Kegiatan Harian</li>
        </ol>
    </div>
</div>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Daftar Jurnal Kegiatan Saya</h3>
        <div class="card-tools">
            <a href="{{ route('peserta.kegiatan.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Tambah Kegiatan
            </a>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Judul Kegiatan</th>
                        <th>Deskripsi</th>
                        <th>Foto</th>
                        <th style-width="120px">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($kegiatan as $item)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}</td>
                        <td>{{ $item->judul_kegiatan }}</td>
                        <td>{{ Str::limit($item->deskripsi, 100) }}</td>
                        <td>
                            @if($item->foto)
                                <a href="{{ asset('storage/kegiatan/' . $item->foto) }}" target="_blank" class="btn btn-secondary btn-xs"><i class="fas fa-eye"></i> Lihat</a>
                            @else
                                <span class="text-muted">N/A</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('peserta.kegiatan.edit', $item->id) }}" class="btn btn-warning btn-sm" title="Edit"><i class="fas fa-edit"></i></a>
                            {{-- <form id="delete-form-{{ $item->id }}" action="{{ route('peserta.kegiatan.destroy', $item->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="btn btn-danger btn-sm btn-delete" data-form-id="delete-form-{{ $item->id }}" title="Hapus"><i class="fas fa-trash-alt"></i></button>
                            </form> --}}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center">Anda belum menambahkan kegiatan. Klik "Tambah Kegiatan" untuk memulai.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer clearfix">
        {{ $kegiatan->links() }}
    </div>
</div>
@endsection

@push('scripts')
{{-- Script untuk konfirmasi hapus menggunakan SweetAlert2 --}}
<script>
    document.querySelectorAll('.btn-delete').forEach(button => {
        button.addEventListener('click', function (event) {
            event.preventDefault();
            const formId = this.getAttribute('data-form-id');
            Swal.fire({
                title: 'Anda yakin?', text: "Kegiatan ini akan dihapus permanen!", icon: 'warning',
                showCancelButton: true, customClass: { confirmButton: 'btn btn-danger', cancelButton: 'btn btn-secondary' },
                buttonsStyling: false, confirmButtonText: 'Ya, hapus!', cancelButtonText: 'Batal', reverseButtons: true
            }).then(result => result.isConfirmed && document.getElementById(formId).submit());
        });
    });
</script>
@endpush