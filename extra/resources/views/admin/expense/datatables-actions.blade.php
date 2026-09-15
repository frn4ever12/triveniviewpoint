<div class="btn-group" role="group">
    <a href="{{ route('admin.expenses.show', $expense->id) }}" class="btn btn-sm btn-info" title="View">
        <i class="bi bi-eye"></i>
    </a>

    <a href="{{ route('admin.expenses.edit', $expense->id) }}" class="btn btn-sm btn-warning" title="Edit">
        <i class="bi bi-pencil"></i>
    </a>

    <button type="button" class="btn btn-sm btn-danger delete-btn" title="Delete"
         data-name="{{ $expense->name }}"
        data-route="{{ route('admin.expenses.destroy', $expense->id) }}"> <i class="bi bi-trash"></i>
    </button>
</div>