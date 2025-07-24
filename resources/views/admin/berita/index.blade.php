@extends('layouts.app')

@section('title', 'Manajemen Berita')

@section('content-header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h1 class="m-0">Manajemen & Persetujuan Berita</h1>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Berita</li>
        </ol>
    </div>
</div>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Daftar Berita & Pengajuan</h3>
        <div class="card-tools">
            <a href="{{ route('admin.berita.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Buat Berita (Admin)
            </a>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover">
                <thead>
                    <tr>
                        <th style="width: 10px">ID</th>
                        <th>Judul & Pengaju</th>
                        <th>Isi Singkat</th>
                        <th>Status</th>
                        <th style="width: 220px">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($berita as $item)
                    <tr>
                        <td>{{ $berita->firstItem() + $loop->index }}</td>
                        <td>
                            <strong>{{ $item->judul }}</strong>
                            <br>
                            <small class="text-muted">
                                @if($item->peserta)
                                    Diajukan oleh: <span class="text-info">{{ $item->peserta->nama }}</span>
                                @else
                                    Dibuat oleh: <span class="text-primary">{{ $item->user->name ?? 'Admin' }}</span>
                                @endif
                            </small>
                        </td>
                        <td>{{ Str::limit($item->paragraf, 100) }}</td>
                        <td>
                            @php
                                $statusClass = 'badge-secondary';
                                if (isset($item->status)) {
                                    switch ($item->status) {
                                        case 'diterbitkan': $statusClass = 'badge-success'; break;
                                        case 'ditolak': $statusClass = 'badge-danger'; break;
                                        case 'menunggu_persetujuan': $statusClass = 'badge-warning'; break;
                                    }
                                }
                            @endphp
                            <span class="badge {{ $statusClass }}">{{ ucfirst(str_replace('_', ' ', $item->status ?? 'Belum Ada Status')) }}</span>
                        </td>
                        <td>
                            {{-- ====================================================== --}}
                            {{--         LOGIKA BARU UNTUK TOMBOL AKSI STATUS           --}}
                            {{-- ====================================================== --}}
                            @if(isset($item->status))
                                @if($item->status == 'diterbitkan')
                                    {{-- Jika SUDAH DITERBITKAN, tampilkan tombol TARIK --}}
                                    <form action="{{ route('admin.berita.updateStatus', $item->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="status" value="menunggu_persetujuan">
                                        <button type="submit" class="btn btn-info btn-xs" title="Tarik dari Publikasi">
                                            <i class="fas fa-undo"></i> Tarik
                                        </button>
                                    </form>
                                @else
                                    {{-- Jika BELUM DITERBITKAN (menunggu atau ditolak), tampilkan tombol PUBLIKASI --}}
                                    <form action="{{ route('admin.berita.updateStatus', $item->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="status" value="diterbitkan">
                                        <button type="submit" class="btn btn-success btn-xs" title="Setujui dan Publikasikan">
                                            <i class="fas fa-check"></i> Publikasi
                                        </button>
                                    </form>
                                @endif

                                {{-- Tampilkan tombol Tolak hanya jika statusnya masih menunggu --}}
                                @if($item->status == 'menunggu_persetujuan')
                                    <form action="{{ route('admin.berita.updateStatus', $item->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="status" value="ditolak">
                                        <button type="submit" class="btn btn-secondary btn-xs" title="Tolak Pengajuan">
                                            <i class="fas fa-times"></i> Tolak
                                        </button>
                                    </form>
                                @endif
                            @endif
                            
                            {{-- Tombol Lihat Foto, Edit, dan Hapus (selalu ada) --}}
                            @if($item->foto)
                                <button class="btn btn-secondary btn-xs" data-toggle="modal" data-target="#fotoModal" data-foto="{{ asset('storage/berita/' . $item->foto) }}" data-judul="{{ $item->judul }}" title="Lihat Foto">
                                    <i class="fas fa-image"></i>
                                </button>
                            @endif
                            
                            <a href="{{ route('admin.berita.edit', $item->id) }}" class="btn btn-warning btn-xs" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form id="delete-form-{{ $item->id }}" action="{{ route('admin.berita.destroy', $item->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="btn btn-danger btn-xs btn-delete" data-form-id="delete-form-{{ $item->id }}" title="Hapus">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center">Belum ada berita.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer clearfix">
        {{ $berita->links() }}
    </div>
</div>

<!-- Modal untuk Lihat Foto -->
<div class="modal fade" id="fotoModal" tabindex="-1" role="dialog" aria-labelledby="fotoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="fotoModalLabel">Foto Berita</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body text-center">
                <img id="modal-foto-img" src="" alt="Foto Berita" class="img-fluid rounded">
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
{{-- Script untuk Modal dan SweetAlert tidak berubah --}}
<script>
    $('#fotoModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var fotoUrl = button.data('foto');
        var judul = button.data('judul');
        var modal = $(this);
        modal.find('.modal-title').text('Foto untuk: ' + judul);
        modal.find('.modal-body img').attr('src', fotoUrl);
    });

    document.querySelectorAll('.btn-delete').forEach(button => {
        button.addEventListener('click', function (event) {
            event.preventDefault();
            const formId = this.getAttribute('data-form-id');
            Swal.fire({
                title: 'Anda yakin?', text: "Berita ini akan dihapus permanen!", icon: 'warning',
                showCancelButton: true, customClass: { confirmButton: 'btn btn-danger', cancelButton: 'btn btn-secondary' },
                buttonsStyling: false, confirmButtonText: 'Ya, hapus!', cancelButtonText: 'Batal', reverseButtons: true
            }).then(result => result.isConfirmed && document.getElementById(formId).submit());
        });
    });
</script>
@endpush