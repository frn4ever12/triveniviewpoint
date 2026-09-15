@extends('frontend.includes.main')

@section('content')
 <!--  preloader with animation -->
 @include('frontend.includes.pre-loader')

 @if(isset($tenant))
    <!-- Single Tenant Mode -->
    <!-- Hero carousel with multiple banners -->
    @include('frontend.welcome.hero')

    <!-- About Section -->
    @include('frontend.welcome.about')

    <!-- Menu Section -->
    @include('frontend.welcome.menu')

    <!-- QR Code Section -->
    @include('frontend.welcome.qrcode')
 @else
    <!-- Multi-Tenant Listing Mode -->
    @include('frontend.welcome.tenants')

    <!-- Features & Pricing Section -->
    @include('frontend.welcome.pricing')

    <!-- QR Code Section -->
    @include('frontend.welcome.qrcode')
 @endif

 <!-- Contact Section (shown in both modes) -->
 @include('frontend.welcome.contact')

@endsection