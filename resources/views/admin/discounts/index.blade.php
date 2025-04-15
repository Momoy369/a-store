@extends('admin.layout')

@section('content-admin')
    <div class="container">
        <h1>Daftar Diskon Produk</h1>
        <a href="{{ route('admin.discounts.create') }}" class="btn btn-primary mb-3">Tambah Diskon</a>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Produk</th>
                    <th>Diskon (%)</th>
                    <th>Tanggal Mulai</th>
                    <th>Tanggal Berakhir</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($discounts as $discount)
                    <tr>
                        <td>{{ $discount->product->name }}</td>
                        <td>{{ $discount->discount_percentage }}%</td>
                        <td>{{ \Carbon\Carbon::parse($discount->start_date)->format('d-m-Y') }}</td>
                        <td>{{ \Carbon\Carbon::parse($discount->end_date)->format('d-m-Y') }}</td>
                        <td>
                            <!-- Tombol Edit -->
                            <a href="{{ route('admin.discounts.edit', $discount->id) }}" class="btn btn-sm btn-warning me-2">
                                <i class="fas fa-edit"></i>
                            </a>

                            <!-- Tombol Hapus Trigger Modal -->
                            <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal"
                                data-bs-target="#deleteModal" data-id="{{ $discount->id }}"
                                data-name="{{ $discount->product->name }}">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Modal untuk Konfirmasi Hapus -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Konfirmasi Hapus Diskon</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Apakah Anda yakin ingin menghapus diskon untuk produk <strong id="product-name"></strong>?
                </div>
                <div class="modal-footer">
                    <form id="deleteForm" method="POST" style="display: inline-block;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Hapus</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        // Script untuk menyiapkan data produk pada modal Hapus
        const deleteModal = document.getElementById('deleteModal');
        deleteModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget; // Tombol yang mengaktifkan modal
            const discountId = button.getAttribute('data-id');
            const productName = button.getAttribute('data-name');

            // Set nama produk pada modal
            const productNameElement = deleteModal.querySelector('#product-name');
            productNameElement.textContent = productName;

            // Set action form untuk menghapus diskon yang benar
            const form = deleteModal.querySelector('#deleteForm');
            form.action = '/admin/discounts/' + discountId;
        });
    </script>
@endsection
