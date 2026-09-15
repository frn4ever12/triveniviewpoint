@extends('admin.includes.main')

@section('title', 'Edit Room')

@section('content')
    <div class="container-fluid">
        <x-breadcrumb title="Edit Room: {{ $room->name }}" route="admin.rooms.index" button="Back to List" icon="bi-arrow-left" />

        <div class="card">
            <div class="card-body">
                <form action="{{ route('admin.rooms.update', $room) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    {{-- Basic Information --}}
                    <h5 class="fw-semibold mb-3"><i class="bi bi-info-circle me-1"></i> Basic Information</h5>
                    <div class="row g-3 mb-4">
                        <div class="col-md-4 col-sm-12">
                            <label for="name" class="form-label">Room Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"
                                name="name" value="{{ old('name', $room->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4 col-sm-12">
                            <label for="room_number" class="form-label">Room Number <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('room_number') is-invalid @enderror" id="room_number"
                                name="room_number" value="{{ old('room_number', $room->room_number) }}" required>
                            @error('room_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4 col-sm-12">
                            <label for="room_type" class="form-label">Room Type <span class="text-danger">*</span></label>
                            <select class="form-select @error('room_type') is-invalid @enderror" id="room_type" name="room_type" required>
                                <option value="">-- Select Type --</option>
                                @foreach (['standard', 'deluxe', 'suite', 'penthouse', 'family', 'single', 'double', 'twin', 'dormitory', 'other'] as $type)
                                    <option value="{{ $type }}" {{ old('room_type', $room->room_type) == $type ? 'selected' : '' }}>
                                        {{ ucfirst($type) }}
                                    </option>
                                @endforeach
                            </select>
                            @error('room_type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-3 col-sm-12">
                            <label for="floor" class="form-label">Floor</label>
                            <input type="text" class="form-control @error('floor') is-invalid @enderror" id="floor"
                                name="floor" value="{{ old('floor', $room->floor) }}">
                            @error('floor')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-3 col-sm-12">
                            <label for="price_per_night" class="form-label">Price per Night (Rs) <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" min="0" class="form-control @error('price_per_night') is-invalid @enderror"
                                id="price_per_night" name="price_per_night" value="{{ old('price_per_night', $room->price_per_night) }}" required>
                            @error('price_per_night')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-3 col-sm-12">
                            <label for="capacity" class="form-label">Capacity (Guests) <span class="text-danger">*</span></label>
                            <input type="number" min="1" max="100" class="form-control @error('capacity') is-invalid @enderror"
                                id="capacity" name="capacity" value="{{ old('capacity', $room->capacity) }}" required>
                            @error('capacity')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-3 col-sm-12">
                            <label for="bed_count" class="form-label">Bed Count <span class="text-danger">*</span></label>
                            <input type="number" min="0" max="20" class="form-control @error('bed_count') is-invalid @enderror"
                                id="bed_count" name="bed_count" value="{{ old('bed_count', $room->bed_count) }}" required>
                            @error('bed_count')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4 col-sm-12">
                            <label for="bed_type" class="form-label">Bed Type</label>
                            <select class="form-select @error('bed_type') is-invalid @enderror" id="bed_type" name="bed_type">
                                <option value="">-- Select Bed Type --</option>
                                @foreach (['single', 'double', 'queen', 'king', 'twin', 'bunk', 'sofa', 'floor', 'other'] as $bt)
                                    <option value="{{ $bt }}" {{ old('bed_type', $room->bed_type) == $bt ? 'selected' : '' }}>
                                        {{ ucfirst($bt) }}
                                    </option>
                                @endforeach
                            </select>
                            @error('bed_type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Amenities --}}
                    <h5 class="fw-semibold mb-3"><i class="bi bi-star me-1"></i> Amenities & Features</h5>
                    <div class="row g-3 mb-4">
                        <div class="col-12">
                            <div class="row">
                                @php
                                    $amenities = [
                                        'has_ac' => 'Air Conditioning',
                                        'has_tv' => 'Television',
                                        'has_wifi' => 'WiFi',
                                        'has_minibar' => 'Minibar',
                                        'has_balcony' => 'Balcony',
                                        'is_smoking_allowed' => 'Smoking Allowed',
                                        'is_wheelchair_accessible' => 'Wheelchair Accessible',
                                    ];
                                @endphp
                                @foreach ($amenities as $field => $label)
                                    <div class="col-md-3 col-sm-6">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" role="switch"
                                                id="{{ $field }}" name="{{ $field }}" value="1"
                                                {{ old($field, $room->$field) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="{{ $field }}">{{ $label }}</label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    {{-- Status & Description --}}
                    <h5 class="fw-semibold mb-3"><i class="bi bi-gear me-1"></i> Status & Details</h5>
                    <div class="row g-3 mb-4">
                        <div class="col-md-4 col-sm-12">
                            <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                            <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                                @foreach (['available' => 'Available', 'occupied' => 'Occupied', 'maintenance' => 'Under Maintenance', 'reserved' => 'Reserved'] as $val => $label)
                                    <option value="{{ $val }}" {{ old('status', $room->status) == $val ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-8 col-sm-12">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description"
                                name="description" rows="3">{{ old('description', $room->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12">
                            <label for="notes" class="form-label">Internal Notes</label>
                            <textarea class="form-control @error('notes') is-invalid @enderror" id="notes"
                                name="notes" rows="2">{{ old('notes', $room->notes) }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-2"></i> Update Room
                        </button>
                        <a href="{{ route('admin.rooms.index') }}" class="btn btn-danger">
                            <i class="bi bi-x-lg me-2"></i> Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
