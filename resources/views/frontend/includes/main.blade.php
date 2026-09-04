<!DOCTYPE html>
<html lang="en">

<head>
    @yield('head')
    @include('frontend.includes.top')
    @stack('styles')

</head>

<body>
    @include('frontend.includes.navbar')
    <main>
        @yield('content')
    </main>
    @include('frontend.includes.footer')
    @include('frontend.includes.bottom')
    @include('admin.includes.toaster')
    @stack('scripts')
</body>

</html>
