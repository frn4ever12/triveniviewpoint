<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"
    integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p"
    crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/feather-icons@4.29.1/dist/feather.min.js"></script>
<script src="{{ asset('assets/js/main.js') }}?v={{ time() }}"></script>
<script src="{{ asset('assets/js/feather.js') }}?v={{ time() }}"></script>
<script src="{{ asset('assets/js/toaster.js') }}?v={{ time() }}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{ asset('assets/js/delete-handler.js') }}?v={{ time() }}"></script>

<script src="{{ asset('assets/js/nepali.datepicker.v5.0.6.min.js') }}?v={{ time() }}"></script>
<script src="{{ asset('assets/js/npdate.js') }}?v={{ time() }}"></script>

<!-- Service Worker disabled temporarily to force cache refresh -->
<script>
    if ('serviceWorker' in navigator) {
        navigator.serviceWorker.getRegistrations().then(function(registrations) {
            for(let registration of registrations) {
                registration.unregister();
            }
        });
    }
</script>
