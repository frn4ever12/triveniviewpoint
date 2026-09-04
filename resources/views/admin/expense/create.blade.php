@extends('admin.includes.main')
@section('title', 'Create Expense')
@section('content')
    <div class="container-fluid">
        <x-breadcrumb title="Create New Expense" route="admin.expenses.index" button="Back to List" icon="bi-arrow-left" />
        <div class="card">
            <div class="card-body">
                <form action="{{ route('admin.expenses.store') }}" method="POST" enctype="multipart/form-data"
                    id="expense-form">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-6 col-sm-12">
                            <label for="expense_number" class="form-label">Expense Number</label>
                            <input type="text" class="form-control @error('expense_number') is-invalid @enderror"
                                id="expense_number" name="expense_number" value="{{ old('expense_number') }}"
                                placeholder="Auto-generated if left empty">
                            @error('expense_number')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            <div class="form-text">Leave empty for auto-generation</div>
                        </div>
                        <div class="col-md-6 col-sm-12">
                            <x-nepali-datepicker name="expense_date" label="Expense Date" :required="true"
                                value_ad="{{ old('expense_date') }}" value_bs="{{ old('expense_date_bs') }}" />
                            @error('expense_date_bs')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6 col-sm-12">
                            <label for="title" class="form-label">Title <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror" id="title"
                                name="title" value="{{ old('title') }}" required>
                            @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6 col-sm-12">
                            <label for="label_id" class="form-label">Label</label>
                            <select name="label_id" class="form-select @error('label_id') is-invalid @enderror">
                                <option value="">Select label</option>
                                @foreach (App\Models\Label::orderBy('name')->get(['id', 'name']) as $label)
                                    <option value="{{ $label->id }}" {{ old('label_id') == $label->id ? 'selected' : '' }}>{{ $label->name }}</option>
                                @endforeach
                            </select>
                            @error('label_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6 col-sm-12">
                            <label for="supplier_id" class="form-label">Supplier</label>
                            <select name="supplier_id" class="form-select @error('supplier_id') is-invalid @enderror">
                                <option value="">Select supplier</option>
                                @foreach (App\Models\Supplier::active()->get(['id', 'name']) as $supplier)
                                    <option value="{{ $supplier->id }}" {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>{{ $supplier->name }}</option>
                                @endforeach
                            </select>
                            @error('supplier_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6 col-sm-12">
                            <label for="employee_id" class="form-label">Staff</label>
                            <select name="employee_id" class="form-select @error('employee_id') is-invalid @enderror">
                                <option value="">Select staff</option>
                                @foreach (App\Models\User::orderBy('name')->get(['id', 'name']) as $user)
                                    <option value="{{ $user->id }}" {{ old('employee_id') == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                                @endforeach
                            </select>
                            @error('employee_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6 col-sm-12">
                            <label for="amount" class="form-label">Amount (Rs.) <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" class="form-control @error('amount') is-invalid @enderror"
                                id="amount" name="amount" value="{{ old('amount') }}" required>
                            @error('amount')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6 col-sm-12">
                            <label for="tax_percent" class="form-label">Tax %</label>
                            <input type="number" step="0.01"
                                class="form-control @error('tax_percent') is-invalid @enderror" id="tax_percent"
                                name="tax_percent" value="{{ old('tax_percent', 0) }}" min="0" max="100">
                            @error('tax_percent')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6 col-sm-12">
                            <label for="tax_amount" class="form-label">Tax Amount (Rs.)</label>
                            <input type="number" step="0.01"
                                class="form-control @error('tax_amount') is-invalid @enderror" id="tax_amount"
                                name="tax_amount" value="{{ old('tax_amount', 0) }}" readonly>
                            @error('tax_amount')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            <div class="form-text">Auto-calculated based on amount and tax percentage</div>
                        </div>
                        <div class="col-md-6 col-sm-12">
                            <label class="form-label">Total Amount (Rs.)</label>
                            <input type="number" step="0.01" disabled
                                class="form-control @error('total_amount') is-invalid @enderror" id="total_amount_display"
                                name="total_amount" value="{{ old('total_amount', 0) }}" readonly>
                            @error('total_amount')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6 col-sm-12">
                            <label for="payment_method" class="form-label">Payment Method</label>
                            <select class="form-select @error('payment_method') is-invalid @enderror" id="payment_method"
                                name="payment_method">
                                <option value="">Select Payment Method</option>
                                <option value="cash" {{ old('payment_method') == 'cash' ? 'selected' : '' }}>Cash</option>
                                <option value="bank_transfer" {{ old('payment_method') == 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                                <option value="card" {{ old('payment_method') == 'card' ? 'selected' : '' }}>Card</option>
                                <option value="check" {{ old('payment_method') == 'check' ? 'selected' : '' }}>Cheque</option>
                                <option value="digital_wallet" {{ old('payment_method') == 'digital_wallet' ? 'selected' : '' }}>Digital Wallet</option>
                            </select>
                            @error('payment_method')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6 col-sm-12">
                            <label for="payment_reference" class="form-label">Payment Reference</label>
                            <input type="text" class="form-control @error('payment_reference') is-invalid @enderror"
                                id="payment_reference" name="payment_reference" value="{{ old('payment_reference') }}"
                                placeholder="Transaction ID, Check number, etc.">
                            @error('payment_reference')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6 col-sm-12">
                            <x-nepali-datepicker name="payment_date" label="Payment Date" :required="true"
                                value_ad="{{ old('payment_date') }}" value_bs="{{ old('payment_date_bs') }}" />
                            @error('payment_date_bs')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6 col-sm-12">
                            <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                            <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                                <option value="pending" {{ old('status', 'pending') == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="approved" {{ old('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                                <option value="rejected" {{ old('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                <option value="paid" {{ old('status') == 'paid' ? 'selected' : '' }}>Paid</option>
                                <option value="cancelled" {{ old('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                            @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-12 col-sm-12">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description"
                                rows="3" placeholder="Enter detailed description...">{{ old('description') }}</textarea>
                            @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-12 col-sm-12">
                            <label for="remarks" class="form-label">Remarks</label>
                            <textarea class="form-control @error('remarks') is-invalid @enderror" id="remarks" name="remarks" rows="2"
                                placeholder="Enter remarks...">{{ old('remarks') }}</textarea>
                            @error('remarks')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary"><i class="bi bi-save me-2"></i> Create Expense</button>
                        <a href="{{ route('admin.expenses.index') }}" class="btn btn-danger"><i class="bi bi-x-lg me-2"></i> Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const amountInput = document.getElementById('amount');
            const taxPercentInput = document.getElementById('tax_percent');
            const taxAmountInput = document.getElementById('tax_amount');
            const totalAmountDisplay = document.getElementById('total_amount_display');

            function calculateTax() {
                const amount = parseFloat(amountInput.value) || 0;
                const taxPercent = parseFloat(taxPercentInput.value) || 0;
                const taxAmount = Math.round((amount * taxPercent / 100) * 100) / 100;
                const totalAmount = amount - taxAmount;
                taxAmountInput.value = taxAmount.toFixed(2);
                totalAmountDisplay.value = totalAmount.toFixed(2);
            }

            amountInput.addEventListener('input', calculateTax);
            taxPercentInput.addEventListener('input', calculateTax);
            calculateTax();
        });
    </script>
@endpush
