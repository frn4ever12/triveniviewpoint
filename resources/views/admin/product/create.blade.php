@extends('admin.includes.main')

@section('title', 'Create Product')

@section('content')
    <div class="container-fluid">
        <x-breadcrumb title="Create New Product" route="admin.products.index" button="Back to List" icon="bi-arrow-left" />

        <div class="card">
            <div class="card-body">
                <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="row g-3">
                        <div class="col-md-6 col-sm-12">
                            <label for="name" class="form-label">Item Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"
                                name="name" value="{{ old('name') }}" placeholder="Enter name of Stock" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 col-sm-12">
                            <label for="unit_id" class="form-label">Measuring Unit <span class="text-danger">*</span></label>
                            <select name="unit_id" class="form-select @error('unit_id') is-invalid @enderror" required>
                                <option value="">Select an option</option>
                                @foreach (\App\Models\Unit::get(['id', 'name']) as $unit)
                                    <option value="{{ $unit->id }}" {{ old('unit_id') == $unit->id ? 'selected' : '' }}>
                                        {{ $unit->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('unit_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 col-sm-12">
                            <label for="group" class="form-label">Group</label>
                            <select name="group" class="form-select @error('group') is-invalid @enderror">
                                <option value="">Select Group for Item</option>
                                <option value="Raw Materials" {{ old('group') == 'Raw Materials' ? 'selected' : '' }}>Raw Materials</option>
                                <option value="Packaging" {{ old('group') == 'Packaging' ? 'selected' : '' }}>Packaging</option>
                                <option value="Spices" {{ old('group') == 'Spices' ? 'selected' : '' }}>Spices</option>
                                <option value="Beverages" {{ old('group') == 'Beverages' ? 'selected' : '' }}>Beverages</option>
                                <option value="Dairy" {{ old('group') == 'Dairy' ? 'selected' : '' }}>Dairy</option>
                                <option value="Vegetables" {{ old('group') == 'Vegetables' ? 'selected' : '' }}>Vegetables</option>
                                <option value="Meat" {{ old('group') == 'Meat' ? 'selected' : '' }}>Meat</option>
                                <option value="Other" {{ old('group') == 'Other' ? 'selected' : '' }}>Other</option>
                            </select>
                            @error('group')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 col-sm-12">
                            <label for="default_price" class="form-label">Default Price</label>
                            <input type="number" step="0.01" class="form-control @error('default_price') is-invalid @enderror" id="default_price"
                                name="default_price" value="{{ old('default_price') ?? 0 }}" placeholder="0">
                            @error('default_price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 col-sm-12">
                            <label for="multiple_unit" class="form-label">Multiple Unit</label>
                            <div class="form-check mt-2">
                                <input type="checkbox" class="form-check-input @error('multiple_unit') is-invalid @enderror" id="multiple_unit"
                                    name="multiple_unit" value="1" {{ old('multiple_unit') ? 'checked' : '' }}>
                                <label class="form-check-label" for="multiple_unit">Enable Multiple Units</label>
                            </div>
                            @error('multiple_unit')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-12 col-sm-12">
                            <label for="description" class="form-label">Description</label>
                            <input type="text" class="form-control @error('description') is-invalid @enderror" id="description"
                                name="description" value="{{ old('description') }}" placeholder="Enter description">
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <h5 class="mt-4 mb-3">Opening Stock</h5>
                    <div class="row g-3">
                        <div class="col-md-4 col-sm-12">
                            <label for="opening_stock_quantity" class="form-label">Quantity</label>
                            <input type="number" step="0.01" class="form-control @error('opening_stock_quantity') is-invalid @enderror" id="opening_stock_quantity"
                                name="opening_stock_quantity" value="{{ old('opening_stock_quantity') ?? 0 }}" placeholder="0">
                            @error('opening_stock_quantity')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4 col-sm-12">
                            <label for="opening_stock_rate" class="form-label">Rate</label>
                            <input type="number" step="0.01" class="form-control @error('opening_stock_rate') is-invalid @enderror" id="opening_stock_rate"
                                name="opening_stock_rate" value="{{ old('opening_stock_rate') ?? 0 }}" placeholder="0">
                            @error('opening_stock_rate')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4 col-sm-12">
                            <label for="opening_stock_value" class="form-label">Value</label>
                            <input type="number" step="0.01" class="form-control bg-light" id="opening_stock_value"
                                name="opening_stock_value" value="{{ (old('opening_stock_quantity') ?? 0) * (old('opening_stock_rate') ?? 0) }}" placeholder="0" readonly>
                        </div>
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-2"></i> Create Product
                        </button>
                        <a href="{{ route('admin.products.index') }}" class="btn btn-danger">
                            <i class="bi bi-x-lg me-2"></i> Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    // Calculate opening stock value automatically
    function calculateOpeningStockValue() {
        const quantity = parseFloat(document.getElementById('opening_stock_quantity').value) || 0;
        const rate = parseFloat(document.getElementById('opening_stock_rate').value) || 0;
        const value = quantity * rate;
        document.getElementById('opening_stock_value').value = value.toFixed(2);
    }

    document.getElementById('opening_stock_quantity').addEventListener('input', calculateOpeningStockValue);
    document.getElementById('opening_stock_rate').addEventListener('input', calculateOpeningStockValue);
</script>
@endpush

