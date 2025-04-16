@extends('admin.layout')
@section('title', 'Edit Pengguna')

@section('content-admin')
    <div class="container-fluid">
        <h1>Edit Pengguna</h1>

        <div class="card mt-3">
            <div class="card-body">
                <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    @include('admin.users.form', ['user' => $user])

                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save"></i> Update
                    </button>
                    <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Kembali</a>
                </form>
            </div>
        </div>
    </div>
@endsection
