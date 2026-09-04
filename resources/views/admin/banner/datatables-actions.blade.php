<div class="btn-group" role="group">

    <a href="{{ route('admin.banners.edit', $banner->id) }}" class="btn btn-sm btn-warning" title="Edit">
        <i class="bi bi-pencil"></i>
    </a>

    <button type="button" class="btn btn-sm btn-danger delete-btn" title="Delete"
         data-name="{{ $banner->title }}"
        data-route="{{ route('admin.banners.destroy', $banner->id) }}"> <i class="bi bi-trash"></i>
    </button>
</div>