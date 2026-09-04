@extends('admin.includes.main')
@section('title', 'Purchase Details')
@section('content')
    <div class="container-fluid">
        <x-breadcrumb title="Purchase Details" route="admin.purchases.index" button="Back to List" icon="bi-arrow-left" />
        <div class="card">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h4 class="mb-0">
                    <i class="bi bi-receipt me-2"></i> {{ $purchase->title }}
                    @if($purchase->invoice_no)
                        <small class="text-muted ms-2">(Invoice: {{ $purchase->invoice_no }})</small>
                    @endif
                </h4>
                <a href="{{ route('admin.purchases.edit', $purchase) }}" class="btn btn-sm btn-warning">
                    <i class="bi bi-pencil me-1"></i> Edit
                </a>
            </div>
            <div class="card-body">
                <div class="row g-4">
                    <div class="col-md-6">
                        <h5 class="mb-3 border-bottom pb-2">Purchase Info</h5>
                        <table class="table table-sm">
                            <tr><th style="width:160px;">Purchase Date</th><td>{{ $purchase->purchase_date?->format('d M Y') ?: $purchase->purchase_date_bs }}</td></tr>
                            <tr><th>Due Date</th><td>{{ $purchase->due_date?->format('d M Y') ?: ($purchase->due_date_bs ?? '—') }}</td></tr>
                            <tr><th>Supplier</th><td>{{ $purchase->supplier?->name ?? '—' }}</td></tr>
                            <tr><th>Payment Status</th><td><span class="badge bg-{{ $purchase->payment_status === 'paid' ? 'success' : 'warning' }}">{{ ucfirst($purchase->payment_status) }}</span></td></tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h5 class="mb-3 border-bottom pb-2">Financial Summary</h5>
                        <table class="table table-sm">
                            <tr><th style="width:160px;">Subtotal</th><td>Rs. {{ number_format($purchase->subtotal, 2) }}</td></tr>
                            <tr><th>VAT ({{ $purchase->vat_percent }}%)</th><td>Rs. {{ number_format($purchase->vat_amount, 2) }}</td></tr>
                            <tr><th>Discount</th><td>Rs. {{ number_format($purchase->discount_amount, 2) }}</td></tr>
                            <tr class="fw-bold"><th>Total Amount</th><td>Rs. {{ number_format($purchase->total_amount, 2) }}</td></tr>
                        </table>
                    </div>
                </div>
                <h5 class="mt-4 mb-3 border-bottom pb-2">Purchase Items</h5>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Product</th>
                                <th class="text-center">Qty</th>
                                <th class="text-end">Rate</th>
                                <th class="text-end">Base</th>
                                <th class="text-end">Disc</th>
                                <th class="text-end">After Disc</th>
                                <th class="text-end">VAT</th>
                                <th class="text-end">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($purchase->items as $i => $item)
                            <tr>
                                <td>{{ $i + 1 }}</td>
                                <td>{{ $item->product?->name ?? '—' }}</td>
                                <td class="text-center">{{ $item->quantity }}</td>
                                <td class="text-end">Rs. {{ number_format($item->unit_rate, 2) }}</td>
                                <td class="text-end">Rs. {{ number_format($item->base_amount, 2) }}</td>
                                <td class="text-end">{{ $item->discount_percent ? $item->discount_percent.'%' : '—' }}</td>
                                <td class="text-end">Rs. {{ number_format($item->amount_after_discount, 2) }}</td>
                                <td class="text-end">{{ $item->vat_percent ? $item->vat_percent.'%' : '—' }}</td>
                                <td class="text-end fw-bold">Rs. {{ number_format($item->total_amount, 2) }}</td>
                            </tr>
                            @empty
                            <tr><td colspan="9" class="text-center text-muted">No items found.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
