@extends('layouts.app')

@section('title', 'Edit Pengguna')

@section('content-header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h1 class="m-0">Edit Pengguna</h1>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.users.index') }}">Pengguna</a></li>
            <li class="breadcrumb-item active">Edit</li>
        </ol>
    </div>
</div>
@endsection

@section('content')
<div class="card card-warning">
    <div class="card-header">
        <h3 class="card-title">Form Edit Pengguna: {{ $user->name }}</h3>
    </div>
    <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
        @method('PUT')
        @include('admin.users._form', ['submitButtonText' => 'Update Pengguna'])
    </form>
</div>
@endsection