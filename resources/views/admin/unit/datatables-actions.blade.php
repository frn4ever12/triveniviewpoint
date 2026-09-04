<div class="btn-group" role="group">

    <a href="{{ route('admin.units.edit', $unit->id) }}" class="btn btn-sm btn-warning" title="Edit">
        <i class="bi bi-pencil"></i>
    </a>

    <button type="button" class="btn btn-sm btn-danger delete-btn" title="Delete"
         data-name="{{ $unit->name }}"
        data-route="{{ route('admin.units.destroy', $unit->id) }}"> <i class="bi bi-trash"></i>
    </button>
</div>