@extends('admin.includes.main')

@section('title', 'Create Recipe')

@section('content')
    <div class="container-fluid">
        <x-breadcrumb title="Create Recipe" route="admin.recipes.index" button="Back" icon="bi-arrow-left" />

        <div class="card">
            <div class="card-body">
                <form action="{{ route('admin.recipes.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Menu Item</label>
                        <select name="menu_item_id" class="form-select" required>
                            <option value="">Select Menu Item</option>
                            @foreach(\App\Models\MenuItem::all() as $item)
                                <option value="{{ $item->id }}">{{ $item->name }} - {{ number_format($item->price, 2) }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Preparation Time (minutes)</label>
                        <input type="number" name="preparation_time" class="form-control" value="15">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Instructions</label>
                        <textarea name="instructions" class="form-control" rows="3"></textarea>
                    </div>

                    <h5 class="mt-4">Recipe Items</h5>
                    <div id="recipe-items">
                        <div class="recipe-item row mb-2">
                            <div class="col-md-4">
                                <select name="items[0][product_id]" class="form-select product-select" required>
                                    <option value="">Select Product</option>
                                    @foreach($products as $product)
                                        <option value="{{ $product->id }}">{{ $product->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <input type="number" name="items[0][quantity]" class="form-control" placeholder="Qty" required step="0.01">
                            </div>
                            <div class="col-md-2">
                                <select name="items[0][unit_id]" class="form-select">
                                    <option value="">Unit</option>
                                    @foreach(\App\Models\Unit::all() as $unit)
                                        <option value="{{ $unit->id }}">{{ $unit->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <input type="number" name="items[0][wastage_percent]" class="form-control" placeholder="Waste %" value="0" step="0.1">
                            </div>
                            <div class="col-md-2">
                                <button type="button" class="btn btn-danger btn-sm remove-item">Remove</button>
                            </div>
                        </div>
                    </div>
                    <button type="button" class="btn btn-secondary btn-sm mt-2" id="add-item">Add Item</button>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary">Create Recipe</button>
                        <a href="{{ route('admin.recipes.index') }}" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        let itemCount = 1;
        document.getElementById('add-item').addEventListener('click', function() {
            const template = `
                <div class="recipe-item row mb-2">
                    <div class="col-md-4">
                        <select name="items[${itemCount}][product_id]" class="form-select product-select" required>
                            <option value="">Select Product</option>
                            @foreach($products as $product)
                                <option value="{{ $product->id }}">{{ $product->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <input type="number" name="items[${itemCount}][quantity]" class="form-control" placeholder="Qty" required step="0.01">
                    </div>
                    <div class="col-md-2">
                        <select name="items[${itemCount}][unit_id]" class="form-select">
                            <option value="">Unit</option>
                            @foreach(\App\Models\Unit::all() as $unit)
                                <option value="{{ $unit->id }}">{{ $unit->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <input type="number" name="items[${itemCount}][wastage_percent]" class="form-control" placeholder="Waste %" value="0" step="0.1">
                    </div>
                    <div class="col-md-2">
                        <button type="button" class="btn btn-danger btn-sm remove-item">Remove</button>
                    </div>
                </div>
            `;
            document.getElementById('recipe-items').insertAdjacentHTML('beforeend', template);
            itemCount++;
        });

        document.getElementById('recipe-items').addEventListener('click', function(e) {
            if (e.target.classList.contains('remove-item')) {
                e.target.closest('.recipe-item').remove();
            }
        });
    </script>
@endpush
