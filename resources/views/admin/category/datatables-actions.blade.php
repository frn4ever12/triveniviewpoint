<div class="btn-group" role="group">
    <a href="{{ route('admin.categories.edit', $category->id) }}" class="btn btn-sm btn-warning" title="Edit">
        <i class="bi bi-pencil"></i>
    </a>
    <button type="button" class="btn btn-sm btn-danger delete-btn" title="Delete"
         data-name="{{ $category->name }}"
        data-route="{{ route('admin.categories.destroy', $category->id) }}"> <i class="bi bi-trash"></i>
    </button>
</div>
