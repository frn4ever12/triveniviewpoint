@extends('admin.includes.main')

@section('title', 'Record Wastage')

@section('content')
    <div class="container-fluid">
        <x-breadcrumb title="Record Wastage" route="admin.wastages.index" button="Back" icon="bi-arrow-left" />

        <div class="card">
            <div class="card-body">
                <form action="{{ route('admin.wastages.store') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Wastage Date</label>
                                <input type="date" name="wastage_date" class="form-control" required value="{{ date('Y-m-d') }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Reason</label>
                                <select name="reason" class="form-select" required>
                                    <option value="">Select Reason</option>
                                    <option value="expired">Expired</option>
                                    <option value="spoiled">Spoiled</option>
                                    <option value="damaged">Damaged</option>
                                    <option value="burnt">Burnt</option>
                                    <option value="overproduction">Overproduction</option>
                                    <option value="preparation_waste">Preparation Waste</option>
                                    <option value="kitchen_waste">Kitchen Waste</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Notes</label>
                        <textarea name="notes" class="form-control" rows="2"></textarea>
                    </div>

                    <h5 class="mt-4">Wastage Items</h5>
                    <div id="wastage-items">
                        <div class="wastage-item row mb-2">
                            <div class="col-md-3">
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
                                <input type="text" name="items[0][batch_number]" class="form-control" placeholder="Batch No">
                            </div>
                            <div class="col-md-2">
                                <button type="button" class="btn btn-danger btn-sm remove-item">Remove</button>
                            </div>
                        </div>
                    </div>
                    <button type="button" class="btn btn-secondary btn-sm mt-2" id="add-item">Add Item</button>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary">Record Wastage</button>
                        <a href="{{ route('admin.wastages.index') }}" class="btn btn-secondary">Cancel</a>
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
                <div class="wastage-item row mb-2">
                    <div class="col-md-3">
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
                        <input type="text" name="items[${itemCount}][batch_number]" class="form-control" placeholder="Batch No">
                    </div>
                    <div class="col-md-2">
                        <button type="button" class="btn btn-danger btn-sm remove-item">Remove</button>
                    </div>
                </div>
            `;
            document.getElementById('wastage-items').insertAdjacentHTML('beforeend', template);
            itemCount++;
        });

        document.getElementById('wastage-items').addEventListener('click', function(e) {
            if (e.target.classList.contains('remove-item')) {
                e.target.closest('.wastage-item').remove();
            }
        });
    </script>
@endpush
