<div class="preloader" id="preloader">
    <div class="preloader-logo">
        @if(isset($logoUrl) && $logoUrl)
            <img src="{{ $logoUrl }}" alt="{{ $siteName ?? '' }}" style="height:48px;">
        @else
            <i class="bi bi-cup-hot-fill"></i>
        @endif
    </div>
    <div class="preloader-spinner"></div>
</div>
