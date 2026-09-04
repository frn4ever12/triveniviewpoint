<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>dmcrestro - Premium Fast Food Experience</title>

<!-- PWA Meta Tags -->
<meta name="theme-color" content="#dc3545">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="default">
<meta name="apple-mobile-web-app-title" content="dmcrestro">
<meta name="description" content="Restaurant Management System - Complete solution for restaurant operations">
<meta name="keywords" content="restaurant, management, pos, orders, menu, digital menu">

<!-- PWA Manifest -->
<link rel="manifest" href="{{ asset('manifest.json') }}">

<!-- Apple Touch Icons -->
<link rel="apple-touch-icon" sizes="72x72" href="{{ asset('icons/icon-72x72.png') }}">
<link rel="apple-touch-icon" sizes="96x96" href="{{ asset('icons/icon-96x96.png') }}">
<link rel="apple-touch-icon" sizes="128x128" href="{{ asset('icons/icon-128x128.png') }}">
<link rel="apple-touch-icon" sizes="144x144" href="{{ asset('icons/icon-144x144.png') }}">
<link rel="apple-touch-icon" sizes="152x152" href="{{ asset('icons/icon-152x152.png') }}">
<link rel="apple-touch-icon" sizes="192x192" href="{{ asset('icons/icon-192x192.png') }}">
<link rel="apple-touch-icon" sizes="384x384" href="{{ asset('icons/icon-384x384.png') }}">
<link rel="apple-touch-icon" sizes="512x512" href="{{ asset('icons/icon-512x512.png') }}">

<!-- Favicon -->
@if($faviconUrl)
    <link rel="icon" href="{{ $faviconUrl }}" type="image/x-icon"/>
@endif
<link rel="icon" type="image/png" sizes="32x32" href="{{ asset('icons/icon-32x32.png') }}">
<link rel="icon" type="image/png" sizes="16x16" href="{{ asset('icons/icon-16x16.png') }}">

<meta name="csrf-token" content="{{ csrf_token() }}">

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Bootstrap Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
  <!-- AOS Animation -->
  <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('assets/css/main.css') }}">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"
    integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>