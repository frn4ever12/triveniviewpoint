<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<meta name="csrf-token" content="{{ csrf_token() }}">

<title>{{ $siteName??'RestaurantPro' }}</title>

@if($faviconUrl)
    <link rel="icon" href="{{ $faviconUrl }}" type="image/x-icon"/>
@endif


<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/overlayscrollbars/css/OverlayScrollbars.min.css">
<script src="https://cdn.jsdelivr.net/npm/overlayscrollbars/js/OverlayScrollbars.min.js" onerror="console.warn('OverlayScrollbars CDN failed to load'); window.OverlayScrollbars = undefined;" defer></script>


<link rel="stylesheet" href="{{ asset('assets/css/theme.css') }}?v={{ time() }}">
<link rel="stylesheet" href="{{ asset('assets/css/datatables.css') }}?v={{ time() }}">