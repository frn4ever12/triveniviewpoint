@extends('admin.includes.main')
@section('title', 'View Expense')
@section('content')
    <div class="container-fluid">
        <x-breadcrumb title="Expense Details" route="admin.expenses.index" button="Back to List" icon="bi-arrow-left" />
        <div class="card">
            <div class="card-header bg-white d-sm-block d-md-flex justify-content-between align-items-center">
                <h3 class="card-title"><i class="bi bi-eye"></i> Expense Details</h3>
                <div class="card-tools">
                    <a href="{{ route('admin.expenses.edit', $expense->id) }}" class="btn btn-sm btn-warning"><i class="bi bi-pencil me-1"></i> Edit</a>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-bordered">
                            <tbody>
                                <tr><th style="width:200px">Date</th><td>{{ $expense->expense_date_bs }} / {{ Carbon\Carbon::parse($expense->expense_date)->format('d-m-Y') }}</td></tr>
                                <tr><th>Label</th><td>{{ $expense->label?->name ?? '—' }}</td></tr>
                                <tr><th>Title</th><td>{{ $expense->title }}</td></tr>
                                <tr><th>Supplier</th><td>{{ $expense->supplier?->name ?? '—' }}</td></tr>
                                <tr><th>Amount (Rs)</th><td>Rs. {{ number_format($expense->amount, 2) }}</td></tr>
                                <tr><th>Tax (Rs)</th><td>Rs. {{ number_format($expense->tax_amount, 2) }}</td></tr>
                                <tr><th>Paid (Rs)</th><td>Rs. {{ number_format($expense->total_amount, 2) }}</td></tr>
                                <tr><th>Payment</th><td>{{ ucfirst($expense->status ?? 'pending') }}</td></tr>
                                <tr><th>Method</th><td>{{ ucwords(str_replace('_', ' ', $expense->payment_method ?? '')) ?: '—' }}</td></tr>
                                <tr><th>Staff</th><td>{{ $expense->staff?->name ?? '—' }}</td></tr>
                                <tr><th>Remark</th><td>{{ $expense->remarks ?? '-' }}</td></tr>
                                <tr><th>Created At</th><td>{{ $expense->created_at->format('d-m-Y H:i:s') }}</td></tr>
                                <tr><th>Updated At</th><td>{{ $expense->updated_at->format('d-m-Y H:i:s') }}</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
