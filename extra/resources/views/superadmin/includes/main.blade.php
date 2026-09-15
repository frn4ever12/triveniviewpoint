<!DOCTYPE html>
<html lang="en">

<head>
    @include('admin.includes.top')
    @yield('title')
    @stack('styles')
</head>

<body>
    <div id="db-wrapper">
        @include('superadmin.includes.sidebar')
        <div id="page-content">
            @include('superadmin.includes.header')
            <div class="mt-10 pb-18">
                @yield('content')
            </div>
        </div>
    </div>
    
    @include('admin.includes.bottom')
    @stack('scripts')
    @include('admin.includes.toaster')

</body>

</html>
