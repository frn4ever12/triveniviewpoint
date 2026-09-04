<div class="btn-group" role="group">
    <a href="{{ route('admin.rooms.edit', $room->id) }}" class="btn btn-sm btn-warning" title="Edit">
        <i class="bi bi-pencil"></i>
    </a>
    <button type="button" class="btn btn-sm btn-danger delete-btn" title="Delete"
         data-name="{{ $room->name }}"
        data-route="{{ route('admin.rooms.destroy', $room->id) }}">
        <i class="bi bi-trash"></i>
    </button>
</div>
