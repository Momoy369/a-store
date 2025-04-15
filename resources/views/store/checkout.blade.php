@extends('layouts.store')

@section('title', 'Checkout')

@section('content')
    <h2>Checkout</h2>

    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <form id="checkout-form">
        @csrf
        <div class="mb-3">
            <label>Nama Lengkap</label>
            <input type="text" name="name" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>No HP</label>
            <input type="text" name="phone" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Alamat</label>
            <textarea name="address" class="form-control" required></textarea>
        </div>
        <button type="submit" class="btn btn-success">Bayar Sekarang</button>
    </form>
@endsection

@section('scripts')
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>
    <script>
        document.getElementById('checkout-form').addEventListener('submit', function(e) {
            e.preventDefault();

            const form = e.target;
            const formData = new FormData(form);

            fetch("{{ route('checkout.process') }}", {
                method: "POST",
                headers: {
                    'X-CSRF-TOKEN': formData.get('_token'),
                },
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if (data.snap_token) {
                    window.snap.pay(data.snap_token, {
                        onSuccess: function(result) {
                            window.location.href = "{{ route('checkout.success') }}";
                        },
                        onPending: function(result) {
                            window.location.href = "{{ route('checkout.success') }}";
                        },
                        onError: function(result) {
                            alert('Terjadi kesalahan pembayaran.');
                        },
                        onClose: function() {
                            alert('Pembayaran dibatalkan.');
                        }
                    });
                } else {
                    alert('Gagal membuat transaksi.');
                }
            })
            .catch(error => {
                console.error(error);
                alert('Terjadi kesalahan server.');
            });
        });
    </script>
@endsection
