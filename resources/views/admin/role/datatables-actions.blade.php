<div class="btn-group btn-group-sm" role="group">
    <a href="{{ route('admin.roles.edit', $role->id) }}" class="btn btn-warning" title="Edit Role">
        <i class="bi bi-pencil-square"></i>
    </a>
    @if (!in_array($role->name, ['superadmin', 'admin']))
        <button type="button" class="btn btn-danger delete-btn" data-id="{{ $role->id }}"
                data-route="{{ route('admin.roles.destroy', $role->id) }}" title="Delete Role">
            <i class="bi bi-trash"></i>
        </button>
    @endif
</div>
