@extends('admin.includes.main')

@section('title', 'Create Room')

@section('content')
    <div class="container-fluid">
        <x-breadcrumb title="Create New Room" route="admin.rooms.index" button="Back to List" icon="bi-arrow-left" />

        <div class="card">
            <div class="card-body">
                <form action="{{ route('admin.rooms.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    {{-- Basic Information --}}
                    <h5 class="fw-semibold mb-3"><i class="bi bi-info-circle me-1"></i> Basic Information</h5>
                    <div class="row g-3 mb-4">
                        <div class="col-md-4 col-sm-12">
                            <label for="name" class="form-label">Room Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"
                                name="name" value="{{ old('name') }}" placeholder="e.g. Ocean View Suite" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4 col-sm-12">
                            <label for="room_number" class="form-label">Room Number <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('room_number') is-invalid @enderror" id="room_number"
                                name="room_number" value="{{ old('room_number') }}" placeholder="e.g. 201" required>
                            @error('room_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4 col-sm-12">
                            <label for="room_type" class="form-label">Room Type <span class="text-danger">*</span></label>
                            <select class="form-select @error('room_type') is-invalid @enderror" id="room_type" name="room_type" required>
                                <option value="">-- Select Type --</option>
                                <option value="standard" {{ old('room_type') == 'standard' ? 'selected' : '' }}>Standard</option>
                                <option value="deluxe" {{ old('room_type') == 'deluxe' ? 'selected' : '' }}>Deluxe</option>
                                <option value="suite" {{ old('room_type') == 'suite' ? 'selected' : '' }}>Suite</option>
                                <option value="penthouse" {{ old('room_type') == 'penthouse' ? 'selected' : '' }}>Penthouse</option>
                                <option value="family" {{ old('room_type') == 'family' ? 'selected' : '' }}>Family</option>
                                <option value="single" {{ old('room_type') == 'single' ? 'selected' : '' }}>Single</option>
                                <option value="double" {{ old('room_type') == 'double' ? 'selected' : '' }}>Double</option>
                                <option value="twin" {{ old('room_type') == 'twin' ? 'selected' : '' }}>Twin</option>
                                <option value="dormitory" {{ old('room_type') == 'dormitory' ? 'selected' : '' }}>Dormitory</option>
                                <option value="other" {{ old('room_type') == 'other' ? 'selected' : '' }}>Other</option>
                            </select>
                            @error('room_type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-3 col-sm-12">
                            <label for="floor" class="form-label">Floor</label>
                            <input type="text" class="form-control @error('floor') is-invalid @enderror" id="floor"
                                name="floor" value="{{ old('floor') }}" placeholder="e.g. 2nd Floor">
                            @error('floor')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-3 col-sm-12">
                            <label for="price_per_night" class="form-label">Price per Night (Rs) <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" min="0" class="form-control @error('price_per_night') is-invalid @enderror"
                                id="price_per_night" name="price_per_night" value="{{ old('price_per_night', '0') }}" required>
                            @error('price_per_night')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-3 col-sm-12">
                            <label for="capacity" class="form-label">Capacity (Guests) <span class="text-danger">*</span></label>
                            <input type="number" min="1" max="100" class="form-control @error('capacity') is-invalid @enderror"
                                id="capacity" name="capacity" value="{{ old('capacity', '2') }}" required>
                            @error('capacity')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-3 col-sm-12">
                            <label for="bed_count" class="form-label">Bed Count <span class="text-danger">*</span></label>
                            <input type="number" min="0" max="20" class="form-control @error('bed_count') is-invalid @enderror"
                                id="bed_count" name="bed_count" value="{{ old('bed_count', '1') }}" required>
                            @error('bed_count')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4 col-sm-12">
                            <label for="bed_type" class="form-label">Bed Type</label>
                            <select class="form-select @error('bed_type') is-invalid @enderror" id="bed_type" name="bed_type">
                                <option value="">-- Select Bed Type --</option>
                                <option value="single" {{ old('bed_type') == 'single' ? 'selected' : '' }}>Single</option>
                                <option value="double" {{ old('bed_type') == 'double' ? 'selected' : '' }}>Double</option>
                                <option value="queen" {{ old('bed_type') == 'queen' ? 'selected' : '' }}>Queen</option>
                                <option value="king" {{ old('bed_type') == 'king' ? 'selected' : '' }}>King</option>
                                <option value="twin" {{ old('bed_type') == 'twin' ? 'selected' : '' }}>Twin</option>
                                <option value="bunk" {{ old('bed_type') == 'bunk' ? 'selected' : '' }}>Bunk</option>
                                <option value="sofa" {{ old('bed_type') == 'sofa' ? 'selected' : '' }}>Sofa Bed</option>
                                <option value="floor" {{ old('bed_type') == 'floor' ? 'selected' : '' }}>Floor Mattress</option>
                                <option value="other" {{ old('bed_type') == 'other' ? 'selected' : '' }}>Other</option>
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
                                <div class="col-md-3 col-sm-6">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" role="switch" id="has_ac"
                                            name="has_ac" value="1" {{ old('has_ac') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="has_ac">Air Conditioning</label>
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-6">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" role="switch" id="has_tv"
                                            name="has_tv" value="1" {{ old('has_tv') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="has_tv">Television</label>
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-6">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" role="switch" id="has_wifi"
                                            name="has_wifi" value="1" {{ old('has_wifi') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="has_wifi">WiFi</label>
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-6">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" role="switch" id="has_minibar"
                                            name="has_minibar" value="1" {{ old('has_minibar') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="has_minibar">Minibar</label>
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-6">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" role="switch" id="has_balcony"
                                            name="has_balcony" value="1" {{ old('has_balcony') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="has_balcony">Balcony</label>
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-6">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" role="switch" id="is_smoking_allowed"
                                            name="is_smoking_allowed" value="1" {{ old('is_smoking_allowed') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_smoking_allowed">Smoking Allowed</label>
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-6">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" role="switch" id="is_wheelchair_accessible"
                                            name="is_wheelchair_accessible" value="1" {{ old('is_wheelchair_accessible') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_wheelchair_accessible">Wheelchair Accessible</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Status & Description --}}
                    <h5 class="fw-semibold mb-3"><i class="bi bi-gear me-1"></i> Status & Details</h5>
                    <div class="row g-3 mb-4">
                        <div class="col-md-4 col-sm-12">
                            <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                            <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                                <option value="available" {{ old('status') == 'available' ? 'selected' : '' }}>Available</option>
                                <option value="occupied" {{ old('status') == 'occupied' ? 'selected' : '' }}>Occupied</option>
                                <option value="maintenance" {{ old('status') == 'maintenance' ? 'selected' : '' }}>Under Maintenance</option>
                                <option value="reserved" {{ old('status') == 'reserved' ? 'selected' : '' }}>Reserved</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-8 col-sm-12">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description"
                                name="description" rows="3" placeholder="Room description, view, special features...">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12">
                            <label for="notes" class="form-label">Internal Notes</label>
                            <textarea class="form-control @error('notes') is-invalid @enderror" id="notes"
                                name="notes" rows="2" placeholder="Internal staff notes...">{{ old('notes') }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-2"></i> Create Room
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
