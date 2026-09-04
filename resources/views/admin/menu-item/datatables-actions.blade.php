<div class="btn-group" role="group">
    <a href="{{ route('admin.menu-items.show', $item->id) }}" class="btn btn-sm btn-info" title="View">
        <i class="bi bi-eye"></i>
    </a>
    <a href="{{ route('admin.menu-items.edit', $item->id) }}" class="btn btn-sm btn-warning" title="Edit">
        <i class="bi bi-pencil"></i>
    </a>
    <button type="button" class="btn btn-sm btn-danger delete-btn" title="Delete"
         data-name="{{ $item->name }}"
        data-route="{{ route('admin.menu-items.destroy', $item->id) }}"> <i class="bi bi-trash"></i>
    </button>
</div>
