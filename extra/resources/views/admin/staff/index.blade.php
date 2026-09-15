@extends('admin.includes.main')
@section('title', 'Staff')
@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/datatables.css') }}">
@endpush
@section('content')
<div class="container-fluid px-3 px-lg-4 py-3">
    <x-breadcrumb title="Staff" route="admin.staff.create" button="Add Staff" icon="bi-plus-circle" />
    <div class="card shadow-sm border-0" style="border-radius:14px;">
        <div class="card-body p-3 p-lg-4">
            {!! $dataTable->table(['class' => 'table align-middle mb-0', 'style' => 'width:100%;font-size:0.85rem;']) !!}
        </div>
    </div>
</div>
@endsection
@push('scripts')
    <script src="{{ asset('assets/js/datatables.min.js') }}"></script>
    {!! $dataTable->scripts() !!}
    <script>
        $(document).ready(function() {
            $('#data-table').on('change', '.login-toggle', function() {
                const id = $(this).data('id');
                const enabled = $(this).is(':checked');
                $.ajax({
                    url: '{{ route('admin.staff.toggle-login') }}',
                    method: 'POST',
                    data: {
                        id: id,
                        login_enabled: enabled,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(res) {
                        if (!res.success) {
                            toastr?.error?.(res.message) || alert(res.message);
                        } else {
                            toastr?.success?.(res.message);
                        }
                    },
                    error: function(xhr) {
                        const msg = xhr.responseJSON?.message || 'Failed to toggle login access.';
                        toastr?.error?.(msg) || alert(msg);
                    }
                });
            });
            let table = $('#data-table').DataTable();
        });
@endpush
