@extends('admin.layout')

@section('content-admin')
    <h4>Edit Kupon</h4>

    <form action="{{ route('admin.coupons.update', $coupon->id) }}" method="POST">
        @csrf
        @method('PUT')
        @include('admin.coupons.form')
        <button type="submit" class="btn btn-primary">Update</button>
        <a href="{{ route('admin.coupons.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
@endsection
