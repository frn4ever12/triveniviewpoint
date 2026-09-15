@extends('admin.includes.main')

@section('title', 'View Menu Item')

@section('content')
<div class="container-fluid">
    <x-breadcrumb title="Menu Item Details" route="admin.menu-items.index" button="Back to List" icon="bi-arrow-left" />

    <div class="card">
        <div class="card-header bg-white d-sm-block d-md-flex justify-content-between align-items-center">
            <h3 class="card-title">
                <i class="fas fa-eye"></i> Menu Item Details
            </h3>
            <div class="card-tools">
                <a href="{{ route('admin.menu-items.edit', $menuItem) }}" class="btn btn-sm btn-warning">
                    <i class="bi bi-pencil me-1"></i> Edit
                </a>
            </div>
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-md-8">
                    <table class="table table-bordered">
                        <tbody>
                            <tr>
                                <th>Name</th>
                                <td>{{ $menuItem->name }}</td>
                            </tr>
                            <tr>
                                <th>Category</th>
                                <td>{{ $menuItem->category->name ?? '—' }}</td>
                            </tr>
                            <tr>
                                <th>Description</th>
                                <td>{{ $menuItem->description ?? '—' }}</td>
                            </tr>
                            <tr>
                                <th>Slug</th>
                                <td><code>{{ $menuItem->slug }}</code></td>
                            </tr>
                            <tr>
                                <th>Price</th>
                                <td>{{ number_format($menuItem->price, 2) }}</td>
                            </tr>
                            <tr>
                                <th>Final Price</th>
                                <td>{{ number_format($menuItem->final_price ?? $menuItem->price, 2) }}</td>
                            </tr>
                            <tr>
                                <th>Discount Type</th>
                                <td>{{ ucfirst($menuItem->discount_type ?? 'none') }}</td>
                            </tr>
                            <tr>
                                <th>Discount Value</th>
                                <td>{{ $menuItem->discount_value ?? '0.00' }}</td>
                            </tr>
                            <tr>
                                <th>Preparation Time</th>
                                <td>{{ $menuItem->preparation_time ?? '—' }} minutes</td>
                            </tr>
                            <tr>
                                <th>Status</th>
                                <td>
                                    <span class="badge bg-{{ $menuItem->status->value === 'active' ? 'success' : 'danger' }}">
                                        {{ $menuItem->status->label() }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <th>Featured</th>
                                <td>
                                    <span class="badge bg-{{ $menuItem->is_featured ? 'primary' : 'secondary' }}">
                                        {{ $menuItem->is_featured ? 'Yes' : 'No' }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <th>Vegetarian</th>
                                <td>{{ $menuItem->is_vegetarian ? 'Yes' : 'No' }}</td>
                            </tr>
                            <tr>
                                <th>Created At</th>
                                <td>{{ $menuItem->created_at->format('d-m-Y H:i:s') }}</td>
                            </tr>
                            <tr>
                                <th>Updated At</th>
                                <td>{{ $menuItem->updated_at->format('d-m-Y H:i:s') }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="col-md-4 text-center">
                    @if ($menuItem->getFirstMediaUrl('image'))
                        <div class="mb-4">
                            <h5>Image</h5>
                            <img src="{{ $menuItem->getFirstMediaUrl('image') }}" alt="Item Image"
                                class="img-fluid img-thumbnail" style="max-width: 100%;">
                        </div>
                    @else
                        <div class="text-muted">
                            <i class="bi bi-gallery-fill fa-3x"></i>
                            <p>No media files</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
