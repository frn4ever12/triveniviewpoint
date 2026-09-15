@extends('admin.includes.main')

@section('title', 'Order Details')

@push('styles')

@endpush

@section('content')
    <div class="container-fluid">
        <x-breadcrumb title="Order Details - #{{ $order->order_no }}" route="admin.orders.index" button="Back to List" icon="bi-arrow-left" />

        <div class="card shadow border-0">
            <div class="card-header bg-white">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="bi bi-receipt me-2"></i>Order #{{ $order->order_no }}</h5>
                    <div>
                        <a href="{{ route('admin.orders.edit', $order->id) }}" class="btn btn-warning btn-sm">
                            <i class="bi bi-pencil me-1"></i>Edit Order
                        </a>
                        <button type="button" class="btn btn-primary btn-sm" onclick="printSection('printableArea')">
                            <i class="bi bi-printer me-2"></i>Print Bill
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-body p-4">
                {{-- Order Overview --}}
                <div class="row g-3 mb-4">
                    <div class="col-md-3">
                        <label class="form-label text-muted">Table</label>
                        <div class="fw-bold"><i class="bi bi-table me-1 text-primary"></i>{{ $order->table->name }}</div>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label text-muted">Waiter</label>
                        <div class="fw-bold"><i class="bi bi-person-badge me-1 text-primary"></i>{{ $order->waiter->name }}</div>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label text-muted">Status</label>
                        <div>
                            <span class="badge 
                                @if($order->status == 'pending') bg-warning
                                @elseif($order->status == 'confirmed') bg-info
                                @elseif($order->status == 'preparing') bg-primary
                                @elseif($order->status == 'ready') bg-success
                                @elseif($order->status == 'served') bg-dark
                                @elseif($order->status == 'completed') bg-success
                                @elseif($order->status == 'cancelled') bg-danger
                                @endif">
                                {{ $order->status }}
                            </span>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label text-muted">Order Date</label>
                        <div class="fw-bold">{{ $order->created_at->format('M d, Y g:i A') }}</div>
                    </div>
                </div>

                @if($order->notes)
                <div class="row mb-4">
                    <div class="col-12">
                        <label class="form-label text-muted">Notes</label>
                        <div class="bg-light p-3 rounded">{{ $order->notes }}</div>
                    </div>
                </div>
                @endif

                {{-- Order Items --}}
                <h5 class="mb-3 text-primary"><i class="bi bi-basket me-2"></i>Order Items</h5>
                <div class="table-responsive mb-4">
                    <table class="table table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th>Dish</th>
                                <th class="text-center">Quantity</th>
                                <th class="text-end">Unit Price</th>
                                <th class="text-end">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->items as $item)
                            <tr>
                                <td>
                                    <div class="fw-bold">{{ $item->dish->name }}</div>
                                    @if($item->dish->description)
                                    <small class="text-muted">{{ $item->dish->description }}</small>
                                    @endif
                                </td>
                                <td class="text-center">{{ $item->quantity }}</td>
                                <td class="text-end">Rs {{ number_format($item->unit_price, 2) }}</td>
                                <td class="text-end fw-bold">Rs {{ number_format($item->total, 2) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Payment & Totals --}}
                <div class="row">
                    <div class="col-md-6">
                        <h5 class="text-primary mb-3"><i class="bi bi-credit-card me-2"></i>Payment Information</h5>
                        <div class="row g-2">
                            <div class="col-6">
                                <label class="form-label text-muted">Payment Method</label>
                                <div class="fw-bold">{{ $order->payment_method ? ucfirst(str_replace('_', ' ', $order->payment_method)) : 'Not specified' }}</div>
                            </div>
                            <div class="col-6">
                                <label class="form-label text-muted">Payment Status</label>
                                <div>
                                    <span class="badge {{ $order->payment_status == 'paid' ? 'bg-success' : 'bg-warning' }}">
                                        {{ ucfirst($order->payment_status ?? 'pending') }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="bg-light p-3 rounded">
                            <div class="d-flex justify-content-between mb-2">
                                <span>Subtotal:</span>
                                <span>Rs {{ number_format($order->subtotal, 2) }}</span>
                            </div>
                            @if($order->vat_percent > 0)
                            <div class="d-flex justify-content-between mb-2">
                                <span>VAT ({{ $order->vat_percent }}%):</span>
                                <span>Rs {{ number_format($order->vat_amount, 2) }}</span>
                            </div>
                            @endif
                            @if($order->discount_amount > 0)
                            <div class="d-flex justify-content-between mb-2 text-success">
                                <span>Discount ({{ $order->discount_type == 'percentage' ? $order->discount_amount.'%' : 'Amount' }}):</span>
                                <span>-Rs {{ number_format($order->discount_amount, 2) }}</span>
                            </div>
                            @endif
                            <hr class="my-2">
                            <div class="d-flex justify-content-between fw-bold fs-5">
                                <span>Total Amount:</span>
                                <span>Rs {{ number_format($order->total_amount, 2) }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Print Section --}}
                <div id="printableArea">
                    {{-- Print-only compact receipt format --}}
                    <style>
                        /* Print styles for compact receipt format */
                        @media print {
                            body {
                                margin: 0;
                                padding: 0;
                                font-family: 'Courier New', monospace;
                                font-size: 11px;
                                line-height: 1.2;
                            }
                            
                            .screen-only {
                                display: none !important;
                            }
                            
                            .print-only {
                                display: block !important;
                            }
                            
                            .btn {
                                display: none !important;
                            }
                            
                            .bill-container {
                                max-width: 400px;
                                width: 100%;
                                box-shadow: none;
                                margin: 0;
                                border-radius: 0;
                                font-family: 'Courier New', monospace;
                            }
                            
                            .print-header {
                                text-align: center;
                                padding: 15px;
                                border-bottom: 2px dashed #333;
                                background: #000;
                                color: white;
                            }
                            
                            .print-header h4 {
                                font-size: 14px;
                                margin-bottom: 5px;
                            }
                            
                            .print-header p {
                                font-size: 10px;
                                margin: 2px 0;
                            }
                            
                            .print-order-info {
                                padding: 10px 15px;
                                font-size: 11px;
                                border-bottom: 1px dashed #333;
                            }
                            
                            .print-items {
                                padding: 10px 15px;
                            }
                            
                            .print-item {
                                display: flex;
                                justify-content: space-between;
                                margin-bottom: 8px;
                                font-size: 11px;
                            }
                            
                            .print-calc {
                                padding: 10px 15px;
                                border-top: 1px dashed #333;
                                font-size: 11px;
                            }
                            
                            .print-calc-row {
                                display: flex;
                                justify-content: space-between;
                                margin-bottom: 5px;
                            }
                            
                            .print-total {
                                font-weight: bold;
                                font-size: 13px;
                                border-top: 2px solid #333;
                                border-bottom: 2px solid #333;
                                padding: 8px 0;
                                margin: 10px 0;
                            }
                            
                            .print-footer {
                                text-align: center;
                                padding: 15px;
                                border-top: 2px dashed #333;
                                font-size: 10px;
                            }
                        }
                        
                        .print-only {
                            display: none;
                        }
                    </style>
                    <div class="print-only">
                        <div class="print-header">
                            <h4>{{ $siteName }}</h4>
                            <p>{{ $address }}</p>
                            <p>Phone: {{ $contactPhone }}</p>
                        </div>

                        <div class="print-order-info">
                            <div style="display: flex; justify-content: space-between; margin-bottom: 3px;">
                                <span>Order: {{ $order->order_no }}</span>
                                <span>Table: {{ $order->table->name }}</span>
                            </div>
                            <div style="display: flex; justify-content: space-between; margin-bottom: 3px;">
                                <span>{{ $order->created_at->format('M d, Y') }}</span>
                                <span>{{ $order->created_at->format('g:i A') }}</span>
                            </div>
                            <div style="display: flex; justify-content: space-between;">
                                <span>Server: {{ $order->waiter->name }}</span>
                                <span>{{ $order->payment_method ? ucfirst(str_replace('_', ' ', $order->payment_method)) : '' }}</span>
                            </div>
                        </div>

                        <div class="print-items">
                            @foreach($order->items as $item)
                            <div class="print-item">
                                <div>
                                    <strong>{{ $item->dish->name }}</strong><br>
                                    <small>{{ $item->quantity }} x Rs {{ number_format($item->unit_price, 2) }}</small>
                                </div>
                                <div>Rs {{ number_format($item->total, 2) }}</div>
                            </div>
                            @endforeach
                        </div>

                        <div class="print-calc">
                            <div class="print-calc-row">
                                <span>Subtotal:</span>
                                <span>Rs {{ number_format($order->subtotal, 2) }}</span>
                            </div>
                            @if($order->vat_amount > 0)
                            <div class="print-calc-row">
                                <span>VAT:</span>
                                <span>Rs {{ number_format($order->vat_amount, 2) }}</span>
                            </div>
                            @endif
                            @if($order->discount_amount > 0)
                            <div class="print-calc-row">
                                <span>Discount:</span>
                                <span>-Rs {{ number_format($order->discount_amount, 2) }}</span>
                            </div>
                            @endif
                            
                            <div class="print-calc-row print-total">
                                <span><strong>TOTAL:</strong></span>
                                <span><strong>Rs {{ number_format($order->total_amount, 2) }}</strong></span>
                            </div>
                        </div>

                        <div class="print-footer">
                            <p><strong>Thank You!</strong></p>
                            <p>Visit Again Soon</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    function printSection(sectionId) {
        const printContent = document.getElementById(sectionId).innerHTML;
        const originalContent = document.body.innerHTML;

        // Replace body with section content
        document.body.innerHTML = printContent;

        // Print only that section
        window.print();

        // Restore original page
        document.body.innerHTML = originalContent;

        // Re-run your scripts (so dates, totals recalc, events restore)
        window.location.reload();
    }
</script>
@endpush