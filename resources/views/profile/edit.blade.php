@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Edit Profil</h2>

        @if (session('status'))
            <div class="alert alert-success">{{ session('status') }}</div>
        @endif

        <form action="{{ route('profile.update') }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="name" class="form-label">Nama</label>
                <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $user->name) }}"
                    required>
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email"
                    value="{{ old('email', $user->email) }}" disabled>
            </div>

            <div class="mb-3">
                <label for="phone" class="form-label">No. Telepon</label>
                <input type="text" class="form-control" id="phone" name="phone"
                    value="{{ old('phone', $user->phone) }}">
            </div>

            <div class="mb-3">
                <label for="address" class="form-label">Alamat</label>
                <textarea class="form-control" id="address" name="address">{{ old('address', $user->address) }}</textarea>
            </div>

            <button type="submit" class="btn btn-primary">Simpan</button>
        </form>
    </div>
@endsection
