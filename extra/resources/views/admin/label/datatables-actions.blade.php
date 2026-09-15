<div class="btn-group" role="group">

    <a href="{{ route('admin.labels.edit', $label->id) }}" class="btn btn-sm btn-warning" title="Edit">
        <i class="bi bi-pencil"></i>
    </a>

    <button type="button" class="btn btn-sm btn-danger delete-btn" title="Delete"
         data-name="{{ $label->name }}"
        data-route="{{ route('admin.labels.destroy', $label->id) }}"> <i class="bi bi-trash"></i>
    </button>
</div>