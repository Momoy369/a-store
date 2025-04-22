@extends('admin.layout')

@section('content-admin')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4>Daftar Kupon</h4>
        <a href="{{ route('admin.coupons.create') }}" class="btn btn-success">+ Tambah Kupon</a>
    </div>

    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Kode</th>
                <th>Tipe</th>
                <th>Nilai</th>
                <th>Terpakai</th>
                <th>Aktif</th>
                <th>Masa Berlaku</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($coupons as $coupon)
                <tr>
                    <td>{{ $coupon->code }}</td>
                    <td>{{ $coupon->type == 'fixed' ? 'Nominal' : 'Persentase' }}</td>
                    <td>
                        {{ $coupon->type == 'fixed' ? 'Rp ' . number_format($coupon->value, 0, ',', '.') : $coupon->value . '%' }}
                    </td>
                    <td>{{ $coupon->used }} / {{ $coupon->max_usage ?? '∞' }}</td>
                    <td>
                        <span class="badge bg-{{ $coupon->is_active ? 'success' : 'secondary' }}">
                            {{ $coupon->is_active ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </td>
                    <td>
                        {{ $coupon->start_date ? \Carbon\Carbon::parse($coupon->start_date)->format('d M Y') : '-' }} -
                        {{ $coupon->end_date ? \Carbon\Carbon::parse($coupon->end_date)->format('d M Y') : '-' }}
                    </td>
                    <td>
                        <a href="{{ route('admin.coupons.edit', $coupon->id) }}" class="btn btn-sm btn-primary">Edit</a>
                        <form action="{{ route('admin.coupons.destroy', $coupon->id) }}" method="POST" class="d-inline"
                            onsubmit="return confirm('Yakin ingin menghapus kupon ini?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger">Hapus</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
