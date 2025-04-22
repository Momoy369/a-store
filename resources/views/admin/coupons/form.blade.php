<div class="mb-3">
    <label>Kode Kupon</label>
    <input type="text" name="code" value="{{ old('code', $coupon->code ?? '') }}" class="form-control" required>
</div>

<div class="mb-3">
    <label>Tipe Diskon</label>
    <select name="type" class="form-control">
        <option value="fixed" {{ old('type', $coupon->type ?? '') == 'fixed' ? 'selected' : '' }}>Nominal</option>
        <option value="percent" {{ old('type', $coupon->type ?? '') == 'percent' ? 'selected' : '' }}>Persentase
        </option>
    </select>
</div>

<div class="mb-3">
    <label>Nilai Diskon</label>
    <input type="number" step="0.01" name="value" value="{{ old('value', $coupon->value ?? '') }}"
        class="form-control" required>
</div>

<div class="mb-3">
    <label>Batas Penggunaan (kosong = tak terbatas)</label>
    <input type="number" name="max_usage" value="{{ old('max_usage', $coupon->max_usage ?? '') }}"
        class="form-control">
</div>

<div class="mb-3">
    <label>Tanggal Mulai</label>
    <input type="datetime-local" name="start_date" class="form-control"
        value="{{ old('start_date', isset($coupon->start_date) ? \Carbon\Carbon::parse($coupon->start_date)->format('Y-m-d\TH:i') : '') }}">
</div>

<div class="mb-3">
    <label>Tanggal Selesai</label>
    <input type="datetime-local" name="end_date" class="form-control"
        value="{{ old('end_date', isset($coupon->end_date) ? \Carbon\Carbon::parse($coupon->end_date)->format('Y-m-d\TH:i') : '') }}">
</div>

<div class="form-check form-switch mb-3">
    <input class="form-check-input" type="checkbox" name="is_active" id="is_active"
        {{ old('is_active', $coupon->is_active ?? true) ? 'checked' : '' }}>
    <label class="form-check-label" for="is_active">Aktif</label>
</div>
