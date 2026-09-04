@extends('admin.includes.main')

@section('title', 'Edit Menu Item')

@section('content')
    <div class="container-fluid">
        <x-breadcrumb title="Edit Menu Item" route="admin.menu-items.index" button="Back to List" icon="bi-arrow-left" />

        <div class="card">
            <div class="card-body">
                <form action="{{ route('admin.menu-items.update', $menuItem) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <h5 class="mb-3">Basic Information</h5>
                    <div class="row g-3">
                        <div class="col-md-6 col-sm-12">
                            <label for="name" class="form-label">Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"
                                name="name" value="{{ old('name', $menuItem->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 col-sm-12">
                            <label for="category_id" class="form-label">Category <span class="text-danger">*</span></label>
                            <select name="category_id" class="form-select @error('category_id') is-invalid @enderror" required>
                                <option value="">Select Category</option>
                                @foreach (\App\Models\Category::get(['id', 'name']) as $cat)
                                    <option value="{{ $cat->id }}"
                                        {{ old('category_id', $menuItem->category_id) == $cat->id ? 'selected' : '' }}>
                                        {{ $cat->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 col-sm-12">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3">{{ old('description', $menuItem->description) }}</textarea>
                            @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <hr class="my-4">

                    <h5 class="mb-3">Financial Information</h5>
                    <div class="row g-3">
                        <div class="col-md-4 col-sm-12">
                            <label for="price" class="form-label">Selling Price <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" class="form-control @error('price') is-invalid @enderror" id="price" name="price" value="{{ old('price', $menuItem->price) }}" required oninput="calculateFinancials()">
                            @error('price')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4 col-sm-12">
                            <label class="form-label">Final Price</label>
                            <input type="text" class="form-control" id="final_price_display" readonly>
                        </div>
                    </div>

                    <hr class="my-4">

                    <h5 class="mb-3">Discount Information</h5>
                    <div class="row g-3">
                        <div class="col-md-3 col-sm-12">
                            <label for="discount_type" class="form-label">Discount Type</label>
                            <select class="form-select @error('discount_type') is-invalid @enderror"
                                    id="discount_type" name="discount_type" onchange="calculateFinancials()">
                                <option value="" {{ old('discount_type', $menuItem->discount_type ?? '') == '' ? 'selected' : '' }}>No Discount</option>
                                <option value="amount" {{ old('discount_type', $menuItem->discount_type ?? '') == 'amount' ? 'selected' : '' }}>Fixed Amount</option>
                                <option value="percentage" {{ old('discount_type', $menuItem->discount_type ?? '') == 'percentage' ? 'selected' : '' }}>Percentage</option>
                            </select>
                            @error('discount_type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-3 col-sm-12">
                            <label for="discount_value" class="form-label">Discount Value</label>
                            <input type="number" step="0.01" class="form-control @error('discount_value') is-invalid @enderror"
                                id="discount_value" name="discount_value" value="{{ old('discount_value', $menuItem->discount_value) }}" oninput="calculateFinancials()">
                            @error('discount_value')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <hr class="my-4">

                    <h5 class="mb-3">Additional Information</h5>
                    <div class="row g-3">
                        <div class="col-md-3 col-sm-12">
                            <label for="preparation_time" class="form-label">Preparation Time</label>
                            <input type="text" class="form-control @error('preparation_time') is-invalid @enderror"
                                id="preparation_time" name="preparation_time" value="{{ old('preparation_time', $menuItem->preparation_time) }}" placeholder="e.g., 15 mins">
                            @error('preparation_time')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 col-sm-12">
                            <label for="image" class="form-label">Image</label>
                            @if ($menuItem->getFirstMediaUrl('image'))
                                <div class="current-image mb-2">
                                    <img src="{{ $menuItem->getFirstMediaUrl('image', 'thumb') }}" alt="Current Image" class="img-thumbnail" style="width: 120px; height: 80px;">
                                    <div class="form-text">Current Image</div>
                                </div>
                            @endif
                            <input type="file" class="form-control @error('image') is-invalid @enderror" id="image" name="image" accept="image/*">
                            <div class="form-text">Max size: 2MB. Recommended: 800x600px</div>
                            @error('image')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="row g-3 mt-2">
                        <div class="col-md-3 col-sm-12">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="is_vegetarian" name="is_vegetarian"
                                    value="1" {{ old('is_vegetarian', $menuItem->is_vegetarian) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_vegetarian">Vegetarian</label>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-12">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="is_featured" name="is_featured"
                                    value="1" {{ old('is_featured', $menuItem->is_featured) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_featured">Is Featured</label>
                            </div>
                        </div>
                    </div>

                    <div class="row g-3 mt-2">
                        <div class="col-md-6 col-sm-12">
                            <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                            <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                                @foreach (\App\Enums\CommonStatusEnum::cases() as $status)
                                    <option value="{{ $status->value }}" {{ old('status', $menuItem->status) == $status->value ? 'selected' : '' }}>
                                        {{ $status->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Update Menu Item
                        </button>
                        <a href="{{ route('admin.menu-items.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    function calculateFinancials() {
        const price = parseFloat(document.getElementById('price')?.value) || 0;
        const discountType = document.getElementById('discount_type')?.value;
        const discountValue = parseFloat(document.getElementById('discount_value')?.value) || 0;

        let finalPrice = price;
        let discountAmount = 0;
        if (discountType && discountValue > 0) {
            if (discountType === 'percentage') {
                discountAmount = (finalPrice * discountValue) / 100;
            } else {
                discountAmount = discountValue;
            }
        }
        finalPrice = finalPrice - discountAmount;
        const finalPriceEl = document.getElementById('final_price_display');
        if (finalPriceEl) finalPriceEl.value = finalPrice.toFixed(2);
    }

    document.addEventListener('DOMContentLoaded', function() {
        calculateFinancials();
        ['price', 'discount_type', 'discount_value'].forEach(id => {
            const el = document.getElementById(id);
            if (el) {
                el.addEventListener('input', calculateFinancials);
                el.addEventListener('change', calculateFinancials);
            }
        });
    });
</script>
@endpush
