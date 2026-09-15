<!DOCTYPE html>
<html lang="en">

<head>
    @yield('head')
    @include('frontend.includes.top')
    @stack('styles')
</head>

<body>
    <main>
        @yield('content')
    </main>
    @include('admin.includes.toaster')
    @stack('scripts')
</body>

</html>
