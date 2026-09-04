<div class="btn-group btn-group-sm" role="group">
    <a href="{{ route('admin.staff.show', $staff->id) }}" class="btn btn-info" title="View Staff">
        <i class="bi bi-eye"></i>
    </a>
    <a href="{{ route('admin.staff.edit', $staff->id) }}" class="btn btn-warning" title="Edit Staff">
        <i class="bi bi-pencil-square"></i>
    </a>
    @if (!in_array($staff->getRoleNames()->first(), ['superadmin']))
        <button type="button" class="btn btn-danger delete-btn" data-id="{{ $staff->id }}"
                data-route="{{ route('admin.staff.destroy', $staff->id) }}" title="Delete Staff">
            <i class="bi bi-trash"></i>
        </button>
    @endif
</div>
