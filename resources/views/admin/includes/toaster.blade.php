@if(session('success'))
    <script>
        showToast('success', '{{ session('success') }}');
    </script>
@endif

@if(session('error'))
    <script>
        showToast('error', '{{ session('error') }}');
    </script>
@endif