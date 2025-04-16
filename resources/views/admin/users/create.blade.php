@extends('admin.layout')
@section('title', 'Tambah Pengguna')

@section('content-admin')
    <div class="container-fluid">
        <h1>Tambah Pengguna</h1>

        <div class="card mt-3">
            <div class="card-body">
                <form action="{{ route('admin.users.store') }}" method="POST">
                    @csrf

                    @include('admin.users.form', ['user' => null])

                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save"></i> Simpan
                    </button>
                    <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Kembali</a>
                </form>
            </div>
        </div>
    </div>
@endsection
