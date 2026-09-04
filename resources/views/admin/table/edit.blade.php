@extends('admin.includes.main')

@section('title', 'Edit Table')

@section('content')
    <div class="container-fluid">
        <x-breadcrumb title="Edit Table" route="admin.tables.index" button="Back to List" icon="bi-arrow-left" />

        <div class="card">
            <div class="card-body">
                <form action="{{ route('admin.tables.update', $table->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row g-3">
                        {{-- Basic Info --}}
                        <div class="col-md-4 col-sm-12">
                            <label for="name" class="form-label">Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror"
                                   id="name" name="name" value="{{ old('name', $table->name) }}" placeholder="Name / Number" required>
                            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>


                        <div class="col-md-4 col-sm-12">
                            <label for="capacity" class="form-label">Capacity <span class="text-danger">*</span></label>
                            <input type="number" min="1" class="form-control @error('capacity') is-invalid @enderror"
                                   id="capacity" name="capacity" value="{{ old('capacity', $table->capacity) }}" required>
                            @error('capacity') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-4 col-sm-12">
                            <label for="table_type" class="form-label">Table Type</label>
                            <select class="form-select @error('table_type') is-invalid @enderror" id="table_type" name="table_type">
                                <option value="regular" {{ old('table_type', $table->table_type) == 'regular' ? 'selected' : '' }}>Regular</option>
                                <option value="vip" {{ old('table_type', $table->table_type) == 'vip' ? 'selected' : '' }}>VIP</option>
                                <option value="outdoor" {{ old('table_type', $table->table_type) == 'outdoor' ? 'selected' : '' }}>Outdoor</option>
                                <option value="private" {{ old('table_type', $table->table_type) == 'private' ? 'selected' : '' }}>Private</option>
                            </select>
                            @error('table_type') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-4 col-sm-12">
                            <label for="status" class="form-label">Table Status</label>
                            <select name="status" id="status" class="form-select">
                                @foreach(\App\Enums\TableStatusEnum::cases() as $status)
                                    <option value="{{ $status->value }}"
                                        {{ old('status', $table->status?->value ?? '') == $status->value ? 'selected' : '' }}>
                                        {{ $status->label() }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Location Info --}}
                        <div class="col-md-4 col-sm-12">
                            <label for="location" class="form-label">Location</label>
                            <input type="text" class="form-control @error('location') is-invalid @enderror"
                                   id="location" name="location" value="{{ old('location', $table->location) }}">
                            @error('location') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-4 col-sm-12">
                            <label for="floor" class="form-label">Floor</label>
                            <input type="text" class="form-control @error('floor') is-invalid @enderror"
                                   id="floor" name="floor" value="{{ old('floor', $table->floor) }}">
                            @error('floor') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-4 col-sm-12">
                            <label for="section" class="form-label">Section</label>
                            <input type="text" class="form-control @error('section') is-invalid @enderror"
                                   id="section" name="section" value="{{ old('section', $table->section) }}">
                            @error('section') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        {{-- Features --}}
                        <div class="col-md-12">
                            <label class="form-label">Features</label>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="has_air_conditioning" name="has_air_conditioning" value="1"
                                    {{ old('has_air_conditioning', $table->has_air_conditioning) ? 'checked' : '' }}>
                                <label class="form-check-label" for="has_air_conditioning">Air Conditioning</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="has_tv" name="has_tv" value="1"
                                    {{ old('has_tv', $table->has_tv) ? 'checked' : '' }}>
                                <label class="form-check-label" for="has_tv">TV</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="has_wifi" name="has_wifi" value="1"
                                    {{ old('has_wifi', $table->has_wifi) ? 'checked' : '' }}>
                                <label class="form-check-label" for="has_wifi">WiFi</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="is_smoking_allowed" name="is_smoking_allowed" value="1"
                                    {{ old('is_smoking_allowed', $table->is_smoking_allowed) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_smoking_allowed">Smoking Allowed</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="is_wheelchair_accessible" name="is_wheelchair_accessible" value="1"
                                    {{ old('is_wheelchair_accessible', $table->is_wheelchair_accessible) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_wheelchair_accessible">Wheelchair Accessible</label>
                            </div>
                        </div>

                       

                        <div class="col-md-4 col-sm-12">
                            <label for="reserved_until" class="form-label">Reserved Until</label>
                            <input type="datetime-local"
                                class="form-control @error('reserved_until') is-invalid @enderror"
                                id="reserved_until" name="reserved_until"
                                value="{{ old('reserved_until', $table->reserved_until ? $table->reserved_until->format('Y-m-d\TH:i') : '') }}">
                            @error('reserved_until') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        

                        {{-- Notes --}}
                        <div class="col-md-6 col-sm-12">
                            <label for="notes" class="form-label">Notes</label>
                            <textarea class="form-control @error('notes') is-invalid @enderror" id="notes" name="notes">{{ old('notes', $table->notes) }}</textarea>
                            @error('notes') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                       

                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-2"></i> Update Table
                        </button>
                        <a href="{{ route('admin.tables.index') }}" class="btn btn-danger">
                            <i class="bi bi-x-lg me-2"></i> Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
