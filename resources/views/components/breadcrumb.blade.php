<div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4 no-print">
    <div>
        <h4 class="fw-bold mb-1" style="color:#1e293b;font-size:1.25rem;">{{ $title }}</h4>
        @if ($slot->isNotEmpty())
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0" style="background:transparent;padding:0;font-size:0.82rem;">
                    {{ $slot }}
                </ol>
            </nav>
        @endif
    </div>
    @if ($route)
        <a href="{{ route($route) }}" class="btn btn-danger d-inline-flex align-items-center gap-2 shadow-sm" style="border-radius:10px;font-weight:600;font-size:0.85rem;padding:0.5rem 1.25rem;">
            @if ($icon)
                <i class="{{ $icon }}"></i>
            @endif
            <span class="d-none d-sm-inline">{{ $button }}</span>
        </a>
    @endif
</div>
