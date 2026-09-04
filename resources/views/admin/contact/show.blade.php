@extends('admin.includes.main')
@section('title', 'View Contact')

@section('content')
    <div class="container-fluid ">
        <x-breadcrumb title="Contact Details" route="admin.contacts.index" button="Back to List" icon="bi-arrow-left" />
        <div class="card mt-3">
            <div class="card-body">
                <h3 class="card-title">{{ $contact->name }}</h3>

                <p><strong>Email:</strong> {{ $contact->email ?? 'N/A' }}</p>
                <p><strong>Phone:</strong> {{ $contact->phone ?? 'N/A' }}</p>
                <p><strong>Subject:</strong> {{ $contact->subject ?? 'N/A' }}</p>
                <p><strong>Message:</strong> {{ $contact->message }}</p>
                <p><strong>Status:</strong>
                    <span class="badge bg-{{ $contact->status === \App\Enums\CommonStatusEnum::ACTIVE ? 'success' : 'danger' }}">
                        {{ $contact->status }}
                    </span>
                </p>

                <div class="mt-4 d-flex gap-2">
                    <!-- Update Status Button -->
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#updateStatusModal">
                        Update Status
                    </button>

                    <!-- Mail To Button -->
                    @if ($contact->email)
                        <a href="mailto:{{ $contact->email }}" class="btn btn-outline-success">
                            Send Email
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="updateStatusModal" tabindex="-1" aria-labelledby="updateStatusModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <form method="POST" action="{{ route('admin.contacts.updateStatus', $contact->id) }}">
                @csrf
                @method('PUT')
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="updateStatusModalLabel">Update Status</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <label for="status" class="form-label">Select Status:</label>
                        <select name="status" id="status" class="form-select">
                            <option value="active" {{ $contact->status == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ $contact->status == 'inactive' ? 'selected' : '' }}>Inactive
                            </option>
                        </select>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Update Status</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
