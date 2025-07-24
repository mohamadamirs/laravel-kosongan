@extends('layouts.app')

@section('title', 'Tambah Pengguna')

@section('content-header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h1 class="m-0">Tambah Pengguna Baru</h1>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.users.index') }}">Pengguna</a></li>
            <li class="breadcrumb-item active">Tambah</li>
        </ol>
    </div>
</div>
@endsection

@section('content')
<div class="card card-primary">
    <div class="card-header">
        <h3 class="card-title">Form Tambah Pengguna</h3>
    </div>
    <form action="{{ route('admin.users.store') }}" method="POST">
        @include('admin.users._form', ['submitButtonText' => 'Tambah Pengguna'])
    </form>
</div>
@endsection