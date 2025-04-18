<div class="variants">
    <h3>Varian Produk</h3>
    <table class="table">
        <thead>
            <tr>
                <th>Nama Varian</th>
                <th>Nilai Varian</th>
                <th>Stok</th>
                <th>Harga</th>
                <th>Diskon</th>
            </tr>
        </thead>
        <tbody>
            @foreach($product->variantCombinations as $combination)
                <tr>
                    <td>
                        @foreach(json_decode($combination->variant_value_ids) as $variantValueId)
                            @php
                                $variantValue = \App\Models\VariantValue::find($variantValueId);
                            @endphp
                            <span>{{ $variantValue->value }}</span>
                            @if(!$loop->last), @endif
                        @endforeach
                    </td>
                    <td>{{ $combination->stock }}</td>
                    <td>{{ $combination->price ?? $product->price }}</td>
                    <td>{{ $combination->discount_type }}: {{ $combination->discount_value }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
