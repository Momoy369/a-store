@extends('layouts.admin')

@section('title', 'Kategori')

@section('content-admin')
    <div class="content-header">
        <div class="container-fluid">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h1 class="m-0">Kategori Produk</h1>
                <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus-circle me-1"></i> Tambah Kategori
                </a>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h3 class="card-title">Daftar Kategori</h3>
            </div>
            <div class="card-body p-0">
                <table class="table table-hover table-striped mb-0">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 60%">Nama</th>
                            <th style="width: 30%" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            // Menampilkan hanya kategori induk
                            $parents = $categories->whereNull('parent_id');
                        @endphp

                        @forelse ($parents as $category)
                            <tr>
                                <td>
                                    <strong>{{ $category->name }}</strong>
                                    @if ($category->subcategories->count() > 0)
                                        <ul class="list-unstyled ms-3 mt-1">
                                            @foreach ($category->subcategories as $subcategory)
                                                <li class="d-flex justify-content-between align-items-center">
                                                    <span class="text-muted">{{ $subcategory->name }}</span>
                                                    <div class="d-flex gap-2">
                                                        <a href="{{ route('admin.categories.edit', $subcategory) }}"
                                                            class="btn btn-sm btn-warning" data-toggle="tooltip"
                                                            title="Edit">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                        <form action="{{ route('admin.categories.destroy', $subcategory) }}"
                                                            method="POST" class="d-inline"
                                                            onsubmit="return confirm('Yakin ingin menghapus subkategori ini?')">
                                                            @csrf @method('DELETE')
                                                            <button class="btn btn-sm btn-danger" data-toggle="tooltip"
                                                                title="Hapus">
                                                                <i class="fas fa-trash-alt"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </li>
                                            @endforeach
                                        </ul>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center">
                                        <a href="{{ route('admin.categories.edit', $category) }}"
                                            class="btn btn-sm btn-warning me-1" data-toggle="tooltip" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.categories.destroy', $category) }}" method="POST"
                                            class="d-inline"
                                            onsubmit="return confirm('Yakin ingin menghapus kategori ini?')">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-sm btn-danger" data-toggle="tooltip" title="Hapus">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="2" class="text-center text-muted">Belum ada kategori.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
