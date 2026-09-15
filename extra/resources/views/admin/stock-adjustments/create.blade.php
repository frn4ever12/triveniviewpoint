@extends('admin.includes.main')

@section('title', 'Create Stock Adjustment')

@section('content')
    <div class="container-fluid">
        <x-breadcrumb title="Create Stock Adjustment" route="admin.stock-adjustments.index" button="Back" icon="bi-arrow-left" />

        <div class="card">
            <div class="card-body">
                <form action="{{ route('admin.stock-adjustments.store') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Adjustment Date</label>
                                <input type="date" name="adjustment_date" class="form-control" required value="{{ date('Y-m-d') }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Adjustment Type</label>
                                <select name="adjustment_type" class="form-select" required>
                                    <option value="increase">Increase Stock</option>
                                    <option value="decrease">Decrease Stock</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Reason</label>
                        <select name="reason" class="form-select" required>
                            <option value="">Select Reason</option>
                            <option value="physical_count">Physical Count</option>
                            <option value="missing_stock">Missing Stock</option>
                            <option value="counting_error">Counting Error</option>
                            <option value="damaged">Damaged</option>
                            <option value="system_correction">System Correction</option>
                            <option value="other">Other</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Notes</label>
                        <textarea name="notes" class="form-control" rows="2"></textarea>
                    </div>

                    <h5 class="mt-4">Adjustment Items</h5>
                    <div id="adjustment-items">
                        <div class="adjustment-item row mb-2">
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
                        <button type="submit" class="btn btn-primary">Create Adjustment</button>
                        <a href="{{ route('admin.stock-adjustments.index') }}" class="btn btn-secondary">Cancel</a>
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
                <div class="adjustment-item row mb-2">
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
            document.getElementById('adjustment-items').insertAdjacentHTML('beforeend', template);
            itemCount++;
        });

        document.getElementById('adjustment-items').addEventListener('click', function(e) {
            if (e.target.classList.contains('remove-item')) {
                e.target.closest('.adjustment-item').remove();
            }
        });
    </script>
@endpush
