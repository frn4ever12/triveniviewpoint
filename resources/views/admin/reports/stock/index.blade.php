@extends('admin.includes.main')

@section('title', 'Stock Report')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/datatables.css') }}">
@endpush

@section('content')
<div class="container-fluid">
    <x-breadcrumb title="Stock Report" route="dashboard" button="Dashboard" icon="bi-arrow-left" />

    {{-- Summary Cards --}}
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card text-white bg-primary">
                <div class="card-body  d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-0 text-white" id="totalItems">{{ $summary['total_items'] }}</h4>
                        <small>Total Items</small>
                    </div>
                    <i class="bi bi-boxes fs-2"></i>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card text-white bg-success">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-0 text-white" id="inStock">{{ $summary['in_stock'] }}</h4>
                        <small>In Stock</small>
                    </div>
                    <i class="bi bi-check-circle fs-2"></i>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card text-white bg-warning">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-0 text-white" id="lowStock">{{ $summary['low_stock'] }}</h4>
                        <small>Low Stock</small>
                    </div>
                    <i class="bi bi-exclamation-triangle fs-2"></i>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card text-white bg-danger">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-0 text-white" id="outOfStock">{{ $summary['out_of_stock'] }}</h4>
                        <small>Out of Stock</small>
                    </div>
                    <i class="bi bi-x-circle fs-2"></i>
                </div>
            </div>
        </div>
    </div>

    {{-- Stock DataTable --}}
    <div class="card">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">Stock Inventory Management</h5>
            <a href="{{ route('admin.products.create') }}" class="btn btn-primary btn-sm">
                <i class="bi bi-plus-lg me-1"></i> Add New Item
            </a>
        </div>
        <div class="card-body py-0 px-2">
            <div class="table-responsive">
                {!! $dataTable->table([
                    'class' => 'table table-striped table-hover',
                    'id'    => 'stock-data-table',
                    'width' => '100%'
                ], true) !!}
            </div>
        </div>
    </div>

    <!-- Manage Stock Usage Modal -->
    <div class="modal fade" id="manageStockModal" tabindex="-1" aria-labelledby="manageStockLabel" aria-hidden="true">
      <div class="modal-dialog">
        <form id="manageStockForm" action="{{ route('admin.reports.stock.update') }}" method="POST">
          @csrf
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="manageStockLabel">
                <i class="bi bi-boxes me-2"></i>Manage Stock Usage
              </h5>
              <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="id" id="stockId">

                <div class="mb-3">
                    <label for="itemName" class="form-label fw-bold">Product Name</label>
                    <input type="text" id="itemName" class="form-control bg-light" readonly>
                </div>

                <div class="row mb-3">
                    <div class="col-md-12">
                        <label for="current_available" class="form-label fw-bold text-success">Current Available Stock</label>
                        <input type="number" id="current_available" class="form-control border-success" readonly>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="add_usage" class="form-label fw-bold text-danger">
                        Add Usage Quantity 
                        <small class="text-muted">(This will reduce current stock)</small>
                    </label>
                    <input type="number" name="add_usage" id="add_usage" class="form-control border-danger" 
                           value="0" min="0" step="1" required>
                    <div class="form-text text-muted">Enter the quantity you want to add to usage</div>
                </div>

            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                <i class="bi bi-x-circle me-1"></i>Cancel
              </button>
              <button type="submit" class="btn btn-primary" id="saveStockBtn">
                <i class="bi bi-check-circle me-1"></i>Update Stock Usage
              </button>
            </div>
          </div>
        </form>
      </div>
    </div>

</div>
@endsection

@push('scripts')
<script src="{{ asset('assets/js/datatables.min.js') }}"></script>
{!! $dataTable->scripts(attributes: ['type' => 'module']) !!}
<script>
// Open modal on manage click
$(document).on('click', '.editStockBtn', function() {
    let id        = $(this).data('id');
    let name      = $(this).data('name');
    let current   = parseFloat($(this).data('current')) || 0;

    $('#stockId').val(id);
    $('#itemName').val(name);
    $('#current_available').val(current);
    $('#add_usage').val(0).attr('max', current);
    $('#preview_remaining').text(current);

    $('#manageStockModal').modal('show');
});

// Live update remaining stock preview
$('#add_usage').on('input', function() {
    let current = parseFloat($('#current_available').val()) || 0;
    let addUsage = parseFloat($(this).val()) || 0;

    // Prevent adding more than current stock
    if (addUsage > current) {
        $(this).val(current);
        addUsage = current;
    }

    let newRemaining = Math.max(0, current - addUsage);
    $('#preview_remaining').text(newRemaining);
    
    // Update preview styling based on remaining stock
    if (newRemaining == 0) {
        $('#preview_remaining').removeClass('text-primary text-warning').addClass('text-danger');
    } else if (newRemaining <= 5) {
        $('#preview_remaining').removeClass('text-primary text-danger').addClass('text-warning');
    } else {
        $('#preview_remaining').removeClass('text-warning text-danger').addClass('text-primary');
    }
});

// Submit stock management form
$('#manageStockForm').on('submit', function(e) {
    e.preventDefault();
    
    let addUsage = parseFloat($('#add_usage').val()) || 0;
    if (addUsage <= 0) {
        return;
    }

    $.ajax({
        url: $(this).attr('action'),
        method: "POST",
        data: $(this).serialize(),
        beforeSend: function() {
            $('#saveStockBtn').prop('disabled', true).html('<i class="bi bi-hourglass-split me-1"></i>Updating...');
        },
        success: function(response) {
            $('#manageStockModal').modal('hide');
            $('#stock-data-table').DataTable().ajax.reload();

            // Update summary cards
            if (response.summary) {
                $('#totalItems').text(response.summary.total_items);
                $('#inStock').text(response.summary.in_stock);
                $('#lowStock').text(response.summary.low_stock);
                $('#outOfStock').text(response.summary.out_of_stock);
            }

        },
        error: function(xhr) {
            let errMsg = "Something went wrong!";
            if (xhr.responseJSON && xhr.responseJSON.message) {
                errMsg = xhr.responseJSON.message;
            }
        },
        complete: function() {
            $('#saveStockBtn').prop('disabled', false).html('<i class="bi bi-check-circle me-1"></i>Update Stock');
        }
    });
});

// Reset form when modal closes
$('#manageStockModal').on('hidden.bs.modal', function() {
    $('#add_usage').val(0);
    $('#preview_remaining').text('0').removeClass('text-warning text-danger').addClass('text-primary');
});
</script>
@endpush