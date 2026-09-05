@extends('admin.includes.main')
@section('title', 'QR Sticker Labels')
@section('content')
<div class="container-fluid">
    <x-breadcrumb title="QR Sticker Labels" route="admin.digital-menu.index" />
    <div class="card mb-4">
        <div class="card-body">
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-2">
                <div>
                    <h5 class="mb-1"><i class="bi bi-upc-scan me-2"></i> Table QR Sticker Labels</h5>
                    <p class="text-muted mb-0 small">Print these labels on sticker paper (A4, 65x40mm per label) and paste on each table.</p>
                </div>
                <button onclick="window.print()" class="btn btn-danger">
                    <i class="bi bi-printer me-1"></i> Print Stickers
                </button>
            </div>
        </div>
    </div>

    <div id="sticker-grid" class="row g-2">
        @foreach ($tables as $table)
        <div class="col-4 col-md-3 col-lg-2 sticker-cell">
            <div class="sticker-label">
                <div class="sticker-header">
                    {{ $table->name }}
                </div>
                <div class="sticker-qr" id="qr-{{ $table->id }}"></div>
                <div class="sticker-footer">
                    Scan to view menu
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>

<style>
.sticker-label {
    border: 1.5px solid #000;
    border-radius: 2px;
    background: #fff;
    padding: 6px 4px 4px;
    text-align: center;
    page-break-inside: avoid;
    break-inside: avoid;
    font-family: 'Courier New', monospace;
}
.sticker-header {
    font-size: 10px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .5px;
    color: #000;
    margin-bottom: 2px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.sticker-qr {
    display: flex;
    justify-content: center;
}
.sticker-qr img, .sticker-qr canvas {
    width: 54px !important;
    height: 54px !important;
}
.sticker-footer {
    font-size: 6.5px;
    color: #555;
    margin-top: 1px;
    text-transform: uppercase;
    letter-spacing: .3px;
}
@media print {
    body { background: #fff !important; }
    .no-print, .navbar, .sidebar, #sidebar, .breadcrumb, .card:first-of-type,
    .container-fluid > .card, footer, .navbar-vertical { display: none !important; }
    .container-fluid { padding: 0 !important; max-width: 100% !important; }
    @page { size: A4; margin: 6mm; }
    #sticker-grid {
        display: flex !important;
        flex-wrap: wrap !important;
    }
    .sticker-cell {
        width: 25% !important;
        flex: 0 0 25% !important;
        max-width: 25% !important;
        padding: 2mm !important;
    }
    .sticker-label {
        border: 1.5px solid #000 !important;
        box-shadow: none !important;
        padding: 4px 3px 3px !important;
    }
    .sticker-qr img, .sticker-qr canvas {
        width: 48px !important;
        height: 48px !important;
    }
}
</style>

<script src="https://cdn.jsdelivr.net/npm/qrcodejs/qrcode.min.js"></script>
<script>
@foreach ($tables as $table)
new QRCode(document.getElementById("qr-{{ $table->id }}"), {
    text: "{{ route('digitalmenu-table', ['slug' => auth()->user()->tenant->slug ?? 'default', 'table' => \Vinkla\Hashids\Facades\Hashids::encode($table->id)]) }}",
    width: 54,
    height: 54,
    colorDark: "#000000",
    colorLight: "#ffffff",
    correctLevel: QRCode.CorrectLevel.H
});
@endforeach
</script>
@endsection
