/**
 * Individual Delete Handler
 * Handles sweet confirmation for individual delete actions in DataTables.
 */
(function () {
    'use strict';

    // Set up CSRF token for all AJAX requests
    if (typeof $ !== 'undefined') {
        $(document).ready(function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
        });
    }

    function initDeleteHandler() {
        if (typeof $ === 'undefined' || typeof Swal === 'undefined') return;

        $(document).on('click', '.delete-btn', function () {
            var deleteRoute = $(this).data('route');
            if (!deleteRoute) return;

            Swal.fire({
                title: 'Do you want to delete this data?',
                text: 'This action cannot be undone!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete!'
            }).then(function (result) {
                if (result.isConfirmed) {
                    $.ajax({
                        url: deleteRoute,
                        method: 'DELETE',
                        success: function (response) {
                            if (typeof showToast === 'function') {
                                showToast('success', response.message || 'Deleted successfully');
                            } else {
                                alert(response.message || 'Deleted successfully');
                            }
                            if ($.fn.DataTable && $('#data-table').length) {
                                $('#data-table').DataTable().ajax.reload();
                            } else {
                                location.reload();
                            }
                        },
                        error: function (xhr) {
                            var msg = 'Failed to delete data';
                            if (xhr.responseJSON && xhr.responseJSON.message) {
                                msg = xhr.responseJSON.message;
                            }
                            if (typeof showToast === 'function') {
                                showToast('error', msg);
                            } else {
                                alert(msg);
                            }
                        }
                    });
                }
            });
        });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initDeleteHandler);
    } else {
        initDeleteHandler();
    }
})();
