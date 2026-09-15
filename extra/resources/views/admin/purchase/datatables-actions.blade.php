<div class="btn-group" role="group">

    <a href="{{ route('admin.purchases.edit', $purchase->id) }}" class="btn btn-sm btn-warning" title="Edit">
        <i class="bi bi-pencil"></i>
    </a>

    <button type="button" class="btn btn-sm btn-danger delete-btn" title="Delete"
         data-name="{{ $purchase->title }}"
        data-route="{{ route('admin.purchases.destroy', $purchase->id) }}"> <i class="bi bi-trash"></i>
    </button>
</div>