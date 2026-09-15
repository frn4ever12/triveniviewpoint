@extends('admin.ktd.layout')

@section('ktd-content')
    @forelse($orders as $order)
        <div class="ktd-card {{ $order->status->value === 'pending' ? 'priority' : ($order->status->value === 'preparing' ? 'preparing' : 'ready') }}"
             data-status="{{ $order->status->value }}"
             data-order-id="{{ $order->id }}">
            <div class="ktd-card-head">
                <div>
                    <div class="order-no">#{{ $order->order_no }}</div>
                    <div class="table-name">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                        {{ $order->table->name ?? 'No Table' }}
                        @if($order->table && $order->table->no_of_guests)
                            &middot; {{ $order->table->no_of_guests }} guests
                        @endif
                    </div>
                </div>
                <span class="badge-status {{ $order->status->value }}">
                    {{ strtoupper($order->status->value === 'pending' ? 'NEW' : $order->status->value) }}
                </span>
            </div>
            <div class="ktd-card-body">
                <ul class="item-list">
                    @foreach($order->items as $item)
                        <li>
                            <span class="qty">{{ $item->quantity }}x</span>
                            <span class="name">{{ $item->menuItem->name ?? $item->dish->name ?? 'Unknown' }}</span>
                            @if($item->size && $item->size == 0.5)
                                <span class="item-status" style="color:#94a3b8;font-size:.65rem;">Half</span>
                            @endif
                            <span class="item-status {{ $item->status ?? 'pending' }}">
                                {{ strtoupper($item->status ?? 'PENDING') }}
                            </span>
                        </li>
                    @endforeach
                </ul>
            </div>
            <div class="ktd-card-foot">
                @if(in_array($order->status->value, ['pending', 'confirmed']))
                    <button class="btn-action btn-prepare" data-order-id="{{ $order->id }}" data-action="prepare">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2L2 7l10 5 10-5-10-5z"/><path d="M2 17l10 5 10-5"/><path d="M2 12l10 5 10-5"/></svg>
                        Prepare
                    </button>
                @endif
                @if(in_array($order->status->value, ['pending', 'preparing', 'confirmed']))
                    <button class="btn-action btn-ready" data-order-id="{{ $order->id }}" data-action="ready">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                        Ready
                    </button>
                @endif
                @if($order->status->value === 'ready')
                    <button class="btn-action btn-served" data-order-id="{{ $order->id }}" data-action="served">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                        Served
                    </button>
                @endif
            </div>
        </div>
    @empty
        <div class="ktd-empty">
            <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" style="opacity:.3;">
                <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/>
            </svg>
            <h3>All Clear</h3>
            <p>No pending orders in the kitchen</p>
        </div>
    @endforelse
@endsection
