@extends('admin.includes.main')

@section('title', 'Edit Purchase')

@section('content')

    <div class="container-fluid">
        <x-breadcrumb title="Edit Purchase" route="admin.purchases.index" button="Back to List" icon="bi-arrow-left" />

        <div class="card">
            <div class="card-body">
                <form action="{{ route('admin.purchases.update', $purchase->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row g-3">
                        <div class="col-md-4">
                            <x-nepali-datepicker name="purchase_date" label="Purchase Date" :required="true"
                            value_bs="{{ $purchase->purchase_date_bs }}" value_ad="{{ $purchase->purchase_date ??'' }}" />
                            @error('purchase_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label for="invoice_no" class="form-label">Invoice No</label>
                            <input type="text" class="form-control @error('invoice_no') is-invalid @enderror" name="invoice_no" value="{{ old('invoice_no', $purchase->invoice_no) }}">
                            @error('invoice_no')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label for="title" class="form-label">Purchase Title <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror" name="title" value="{{ old('title', $purchase->title) }}" required>
                            @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label for="vendor_id" class="form-label">Supplier</label>
                            <select name="vendor_id" class="form-select @error('vendor_id') is-invalid @enderror">
                                <option value="">Select a Supplier</option>
                                @foreach($suppliers as $supplier)
                                    <option value="{{ $supplier->id }}" {{ old('vendor_id', $purchase->vendor_id) == $supplier->id ? 'selected' : '' }}>{{ $supplier->name }}</option>
                                @endforeach
                            </select>
                            @error('vendor_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-3">
                            <x-nepali-datepicker name="due_date" label="Due Date" :required="false"
                                value_bs="{{ $purchase->due_date_bs }}" value_ad="{{ $purchase->due_date ?? '' }}" />
                            @error('due_date_bs')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-3">
                            <label for="vat_percent" class="form-label">VAT %</label>
                            <input type="number" class="form-control @error('vat_percent') is-invalid @enderror" name="vat_percent" value="{{ old('vat_percent', $purchase->vat_percent) }}" min="0" max="100" step="0.01">
                            @error('vat_percent')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-3">
                            <label for="discount_percent" class="form-label">Discount %</label>
                            <input type="number" class="form-control @error('discount_percent') is-invalid @enderror" name="discount_percent" value="{{ old('discount_percent', $purchase->discount_percent) }}" min="0" max="100" step="0.01">
                            @error('discount_percent')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-3">
                            <label for="discount_amount" class="form-label">Discount Amount</label>
                            <input type="number" class="form-control @error('discount_amount') is-invalid @enderror" name="discount_amount" value="{{ old('discount_amount', $purchase->discount_amount) }}" min="0" step="0.01">
                            @error('discount_amount')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-3 col-sm-12">
                            <label for="payment_status" class="form-label">Payment Status</label>
                            <select class="form-select @error('payment_status') is-invalid @enderror" id="payment_status" name="payment_status">
                                <option value="">Select Status</option>
                                <option value="pending" {{ old('payment_status',$purchase->payment_status) == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="paid" {{ old('payment_status',$purchase->payment_status) == 'paid' ? 'selected' : '' }}>Paid</option>
                            </select>
                            @error('payment_status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <hr class="my-4">

                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <h5 class="mb-0">Items</h5>
                        <div class="d-flex gap-2">
                            <button type="button" id="expand-all" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrows-angle-expand me-1"></i> Expand All</button>
                            <button type="button" id="collapse-all" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrows-angle-contract me-1"></i> Collapse All</button>
                            <button type="button" id="add-item" class="btn btn-outline-primary btn-sm"><i class="bi bi-plus-circle me-1"></i> Add Item</button>
                        </div>
                    </div>

                    <div id="items-list" class="row g-3">
                        @foreach ($purchase->items as $index => $item)
                        <div class="col-12 item-card" data-index="{{ $index }}">
                            <div class="border rounded p-0 bg-light">
                                <div class="d-flex justify-content-between align-items-center p-3 border-bottom">
                                    <strong>Item #{{ $index + 1 }}</strong>
                                    <div class="d-flex gap-2">
                                        <button type="button" class="btn btn-sm btn-outline-secondary toggle-body" data-target="#body-{{ $index }}"><i class="bi bi-chevron-down"></i></button>
                                        <button type="button" class="btn btn-sm btn-outline-secondary toggle-advanced" data-target="#advanced-{{ $index }}">Advanced</button>
                                        <button type="button" class="btn btn-sm btn-outline-danger remove-card" title="Remove"><i class="bi bi-x-lg"></i></button>
                                    </div>
                                </div>
                                <div class="p-3 item-body d-none" id="body-{{ $index }}">
                                    <div class="row g-2">
                                        <div class="col-md-4">
                                            <label class="form-label">Product Name</label>
                                            <select name="items[{{ $index }}][product_id]" class="form-select" required>
                                                <option value="">Select Product</option>
                                                @foreach ($products as $product)
                                                    <option value="{{ $product->id }}" 
                                                        {{ $item->product_id == $product->id ? 'selected' : '' }}>
                                                        {{ $product->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-md-2">
                                            <label class="form-label">Qty</label>
                                            <input type="number" name="items[{{ $index }}][quantity]" class="form-control quantity" min="1" value="{{ $item->quantity }}" required>
                                        </div>
                                       
                                        <div class="col-md-3">
                                            <label class="form-label">Unit Rate</label>
                                            <input type="number" name="items[{{ $index }}][unit_rate]" class="form-control unit-rate" min="0" step="0.01" value="{{ $item->unit_rate }}">
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label">Base</label>
                                            <input type="number" name="items[{{ $index }}][base_amount]" class="form-control base-amount" value="{{ $item->base_amount }}" readonly>
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label">Disc %</label>
                                            <input type="number" name="items[{{ $index }}][discount_percent]" class="form-control discount-percent" min="0" max="100" step="0.01" value="{{ $item->discount_percent }}">
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label">Disc Amt</label>
                                            <input type="number" name="items[{{ $index }}][discount_amount]" class="form-control discount-amount" min="0" step="0.01" value="{{ $item->discount_amount }}">
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label">After Disc</label>
                                            <input type="number" name="items[{{ $index }}][amount_after_discount]" class="form-control after-discount" value="{{ $item->amount_after_discount }}" readonly>
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label">VAT %</label>
                                            <input type="number" name="items[{{ $index }}][vat_percent]" class="form-control vat-percent" min="0" max="100" step="0.01" value="{{ $item->vat_percent }}">
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label">VAT Amt</label>
                                            <input type="number" name="items[{{ $index }}][vat_amount]" class="form-control vat-amount" value="{{ $item->vat_amount }}" readonly>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label">Total</label>
                                            <input type="number" name="items[{{ $index }}][total_amount]" class="form-control total-amount" value="{{ $item->total_amount }}" readonly>
                                        </div>
                                    </div>
                                    <div class="row g-2 mt-2 advanced d-none" id="advanced-{{ $index }}">
                                        <div class="col-md-3">
                                            <label class="form-label">Batch</label>
                                            <input type="text" name="items[{{ $index }}][batch_number]" class="form-control" value="{{ $item->batch_number }}">
                                        </div>
                                       
                                        <div class="col-md-3">
                                            <label class="form-label">Add to Inventory</label>
                                            <select name="items[{{ $index }}][add_to_inventory]" class="form-select">
                                                <option value="1" {{ $item->add_to_inventory ? 'selected' : '' }}>Yes</option>
                                                <option value="0" {{ !$item->add_to_inventory ? 'selected' : '' }}>No</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <div class="mt-4">
                        <div class="row g-3 align-items-end">
                            <div class="col-md-3">
                                <label class="form-label">Subtotal</label>
                                <div class="form-control bg-white">Rs. <span id="subtotal-amount">{{ number_format((float)$purchase->subtotal, 2) }}</span></div>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Total VAT</label>
                                <div class="form-control bg-white">Rs. <span id="total-vat">{{ number_format((float)$purchase->vat_amount, 2) }}</span></div>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Grand Total</label>
                                <input type="number" class="form-control" id="grand-total-input" value="{{ number_format((float)$purchase->total_amount, 2, '.', '') }}" readonly>
                            </div>
                        </div>
                    </div>

                    <div class="mt-3">
                        <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i> Update Purchase</button>
                        <a href="{{ route('admin.purchases.index') }}" class="btn btn-secondary"><i class="bi bi-x-lg me-1"></i> Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    function recalcRow(scope) {
        const container = scope.closest('.item-card');
        const qty = parseFloat(container.find('.quantity').val()) || 0;
        const rate = parseFloat(container.find('.unit-rate').val()) || 0;
        const base = qty * rate;
        container.find('.base-amount').val(base.toFixed(2));

        let discPercent = parseFloat(container.find('.discount-percent').val()) || 0;
        let discAmount = parseFloat(container.find('.discount-amount').val()) || 0;
        if (discPercent > 0 && (discAmount === 0)) {
            discAmount = (base * discPercent) / 100;
            container.find('.discount-amount').val(discAmount.toFixed(2));
        }
        const afterDisc = Math.max(0, base - discAmount);
        container.find('.after-discount').val(afterDisc.toFixed(2));

        const vatPercent = parseFloat(container.find('.vat-percent').val()) || 0;
        const vatAmount = (afterDisc * vatPercent) / 100;
        container.find('.vat-amount').val(vatAmount.toFixed(2));

        const total = afterDisc + vatAmount;
        container.find('.total-amount').val(total.toFixed(2));
    }

    function recalcTotals() {
        let subtotal = 0;
        let totalVat = 0;

        $('#items-list .item-card').each(function () {
            subtotal += parseFloat($(this).find('.after-discount').val()) || 0;
            totalVat += parseFloat($(this).find('.vat-amount').val()) || 0;
        });

        const headerVatPercent = parseFloat(document.querySelector('input[name="vat_percent"]').value) || 0;
        if (headerVatPercent > 0) {
            totalVat = (subtotal * headerVatPercent) / 100;
        }
        let discountPercent = parseFloat(document.querySelector('input[name="discount_percent"]').value) || 0;
        let discountAmount = parseFloat(document.querySelector('input[name="discount_amount"]').value) || 0;
        if (discountPercent > 0 && discountAmount === 0) {
            discountAmount = (subtotal * discountPercent) / 100;
            document.querySelector('input[name="discount_amount"]').value = discountAmount.toFixed(2);
        }

        const grandTotal = subtotal + totalVat - discountAmount;
        document.getElementById('subtotal-amount').innerText = subtotal.toFixed(2);
        document.getElementById('total-vat').innerText = totalVat.toFixed(2);
        document.getElementById('grand-total-input').value = grandTotal.toFixed(2);
    }

    function buildItemCard(idx) {
        return `
        <div class=\"col-12 item-card\" data-index=\"${idx}\">
            <div class=\"border rounded p-0 bg-light\">
                <div class=\"d-flex justify-content-between align-items-center p-3 border-bottom\">
                    <strong>Item #${idx + 1}</strong>
                    <div class=\"d-flex gap-2\">
                        <button type=\"button\" class=\"btn btn-sm btn-outline-secondary toggle-body\" data-target=\"#body-${idx}\"><i class=\"bi bi-chevron-down\"></i></button>
                        <button type=\"button\" class=\"btn btn-sm btn-outline-secondary toggle-advanced\" data-target=\"#advanced-${idx}\">Advanced</button>
                        <button type=\"button\" class=\"btn btn-sm btn-outline-danger remove-card\" title=\"Remove\"><i class=\"bi bi-x-lg\"></i></button>
                    </div>
                </div>
                <div class=\"p-3 item-body d-none\" id=\"body-${idx}\">
                    <div class=\"row g-2\">
                        <div class=\"col-md-4\">
                            <label class=\"form-label\">Item Name</label>
                            <input type=\"text\" name=\"items[${idx}][item_name]\" class=\"form-control\" required>
                        </div>
                        <div class=\"col-md-2\">
                            <label class=\"form-label\">Qty</label>
                            <input type=\"number\" name=\"items[${idx}][quantity]\" class=\"form-control quantity\" min=\"1\" value=\"1\" required>
                        </div>
                        <div class=\"col-md-3\">
                            <label class=\"form-label\">Unit</label>
                            <select name=\"items[${idx}][unit_id]\" class=\"form-select\" required>
                                <option value=\"\">Select Unit</option>
                                @foreach($units as $unit)
                                    <option value=\"{{ $unit->id }}\">{{ $unit->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class=\"col-md-3\">
                            <label class=\"form-label\">Unit Rate</label>
                            <input type=\"number\" name=\"items[${idx}][unit_rate]\" class=\"form-control unit-rate\" min=\"0\" step=\"0.01\" value=\"0\">
                        </div>
                        <div class=\"col-md-3\">
                            <label class=\"form-label\">Base</label>
                            <input type=\"number\" name=\"items[${idx}][base_amount]\" class=\"form-control base-amount\" readonly>
                        </div>
                        <div class=\"col-md-2\">
                            <label class=\"form-label\">Disc %</label>
                            <input type=\"number\" name=\"items[${idx}][discount_percent]\" class=\"form-control discount-percent\" min=\"0\" max=\"100\" step=\"0.01\" value=\"0\">
                        </div>
                        <div class=\"col-md-3\">
                            <label class=\"form-label\">Disc Amt</label>
                            <input type=\"number\" name=\"items[${idx}][discount_amount]\" class=\"form-control discount-amount\" min=\"0\" step=\"0.01\" value=\"0\">
                        </div>
                        <div class=\"col-md-3\">
                            <label class=\"form-label\">After Disc</label>
                            <input type=\"number\" name=\"items[${idx}][amount_after_discount]\" class=\"form-control after-discount\" readonly>
                        </div>
                        <div class=\"col-md-2\">
                            <label class=\"form-label\">VAT %</label>
                            <input type=\"number\" name=\"items[${idx}][vat_percent]\" class=\"form-control vat-percent\" min=\"0\" max=\"100\" step=\"0.01\" value=\"0\">
                        </div>
                        <div class=\"col-md-3\">
                            <label class=\"form-label\">VAT Amt</label>
                            <input type=\"number\" name=\"items[${idx}][vat_amount]\" class=\"form-control vat-amount\" readonly>
                        </div>
                        <div class=\"col-md-3\">
                            <label class=\"form-label\">Total</label>
                            <input type=\"number\" name=\"items[${idx}][total_amount]\" class=\"form-control total-amount\" readonly>
                        </div>
                    </div>
                    <div class=\"row g-2 mt-2 advanced d-none\" id=\"advanced-${idx}\">
                        <div class=\"col-md-3\">
                            <label class=\"form-label\">Batch</label>
                            <input type=\"text\" name=\"items[${idx}][batch_number]\" class=\"form-control\">
                        </div>
                       
                        <div class=\"col-md-3\">
                            <label class=\"form-label\">Add to Inventory</label>
                            <select name=\"items[${idx}][add_to_inventory]\" class=\"form-select\">
                                <option value=\"1\" selected>Yes</option>
                                <option value=\"0\">No</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>`;
    }

    $(document).on('input', '.quantity, .unit-rate, .discount-percent, .discount-amount, .vat-percent, input[name="vat_percent"], input[name="discount_percent"], input[name="discount_amount"]', function () {
        recalcRow($(this));
        recalcTotals();
    });

    $(document).on('click', '.toggle-advanced', function () {
        const target = $(this).data('target');
        $(target).toggleClass('d-none');
    });
    $(document).on('click', '.toggle-body', function () {
        const target = $(this).data('target');
        $(target).toggleClass('d-none');
    });
    $('#expand-all').on('click', function () { $('.item-body').removeClass('d-none'); });
    $('#collapse-all').on('click', function () { $('.item-body').addClass('d-none'); });

    $(document).on('click', '.remove-card', function () {
        $(this).closest('.item-card').remove();
        recalcTotals();
    });

    $('#add-item').on('click', function () {
        let index = $('#items-list .item-card').length;
        $('#items-list').append(buildItemCard(index));
        recalcTotals();
    });

    $(document).ready(function() { recalcTotals(); });
</script>
@endpush
