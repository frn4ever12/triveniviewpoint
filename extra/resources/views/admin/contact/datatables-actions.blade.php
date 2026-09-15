<div class="btn-group" role="group">
    <a href="{{ route('admin.contacts.show', $contact->id) }}" class="btn btn-sm btn-info" title="View">
        <i class="bi bi-eye"></i>
    </a>

    <button type="button" class="btn btn-sm btn-danger delete-btn" title="Delete"
         data-name="{{ $contact->id }}"
        data-route="{{ route('admin.contacts.destroy', $contact->id) }}"> <i class="bi bi-trash"></i>
    </button>
</div>