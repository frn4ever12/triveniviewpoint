@extends('admin.includes.main')

@section('title', 'Record Kitchen Consumption')

@section('content')
    <div class="container-fluid">
        <x-breadcrumb title="Record Kitchen Consumption" route="admin.kitchen-consumptions.index" button="Back" icon="bi-arrow-left" />

        <div class="card">
            <div class="card-body">
                <form action="{{ route('admin.kitchen-consumptions.store') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Consumption Date</label>
                                <input type="date" name="consumption_date" class="form-control" required value="{{ date('Y-m-d') }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Kitchen Department</label>
                                <input type="text" name="kitchen_department" class="form-control" placeholder="e.g., Main Kitchen, Pastry">
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Reason</label>
                        <input type="text" name="reason" class="form-control" placeholder="e.g., Daily prep, Special event">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Notes</label>
                        <textarea name="notes" class="form-control" rows="2"></textarea>
                    </div>

                    <h5 class="mt-4">Consumption Items</h5>
                    <div id="consumption-items">
                        <div class="consumption-item row mb-2">
                            <div class="col-md-4">
                                <select name="items[0][product_id]" class="form-select" required>
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
                                <button type="button" class="btn btn-danger btn-sm remove-item">Remove</button>
                            </div>
                        </div>
                    </div>
                    <button type="button" class="btn btn-secondary btn-sm mt-2" id="add-item">Add Item</button>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary">Record Consumption</button>
                        <a href="{{ route('admin.kitchen-consumptions.index') }}" class="btn btn-secondary">Cancel</a>
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
                <div class="consumption-item row mb-2">
                    <div class="col-md-4">
                        <select name="items[${itemCount}][product_id]" class="form-select" required>
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
                        <button type="button" class="btn btn-danger btn-sm remove-item">Remove</button>
                    </div>
                </div>
            `;
            document.getElementById('consumption-items').insertAdjacentHTML('beforeend', template);
            itemCount++;
        });

        document.getElementById('consumption-items').addEventListener('click', function(e) {
            if (e.target.classList.contains('remove-item')) {
                e.target.closest('.consumption-item').remove();
            }
        });
    </script>
@endpush
