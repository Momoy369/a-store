@extends('admin.layout')

@section('content-admin')
    <h4>Tambah Kupon Baru</h4>

    <form action="{{ route('admin.coupons.store') }}" method="POST">
        @csrf
        @include('admin.coupons.form')
        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="{{ route('admin.coupons.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
@endsection
