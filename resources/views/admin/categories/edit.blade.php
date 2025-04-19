@extends('admin.layout')

@section('title', 'Edit Kategori')

@section('content-admin')
    <div class="container-fluid">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title">Edit Kategori</h3>
            </div>
            <form action="{{ route('admin.categories.update', $category) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="card-body">
                    <div class="form-group">
                        <label for="name">Nama Kategori</label>
                        <input type="text" name="name" id="name"
                            class="form-control @error('name') is-invalid @enderror"
                            value="{{ old('name', $category->name) }}" placeholder="Contoh: Elektronik, Fashion, Buku"
                            required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="subcategories">Subkategori</label>
                        <select name="parent_id" id="parent_id"
                            class="form-control @error('parent_id') is-invalid @enderror">
                            <option value="">Pilih Kategori Induk (Opsional)</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}"
                                    {{ old('parent_id', $category->parent_id) == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('subcategories')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">Pilih kategori lain yang ingin dijadikan subkategori dari
                            kategori ini.</small>
                    </div>

                </div>
                <div class="card-footer d-flex justify-content-between">
                    <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left mr-1"></i> Kembali
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save mr-1"></i> Update
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
