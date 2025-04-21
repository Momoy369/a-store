<div class="text-center">
    <a href="{{ route('admin.products.show', $product) }}" class="btn btn-sm btn-outline-secondary mb-1">
        <i class="fas fa-eye"></i>
    </a>
    <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-sm btn-warning mb-1">
        <i class="fas fa-edit"></i>
    </a>
    <form action="{{ route('admin.products.toggleStatus', $product) }}" method="POST" class="d-inline mb-1">
        @csrf
        @method('PATCH')
        <button type="submit" class="btn btn-sm {{ $product->is_active ? 'btn-success' : 'btn-secondary' }}">
            <i class="fas fa-toggle-{{ $product->is_active ? 'on' : 'off' }}"></i>
        </button>
    </form>
    <button type="button" class="btn btn-sm btn-danger mb-1" data-bs-toggle="modal" data-bs-target="#deleteModal"
        data-id="{{ $product->id }}" data-name="{{ $product->name }}">
        <i class="fas fa-trash-alt"></i>
    </button>
</div>
