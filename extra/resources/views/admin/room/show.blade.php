@extends('admin.includes.main')

@section('title', 'Room Details')

@section('content')
    <div class="container-fluid">
        <x-breadcrumb title="Room Details: {{ $room->name }}" route="admin.rooms.index" button="Back to List" icon="bi-arrow-left" />

        <div class="card">
            <div class="card-header bg-white d-sm-block d-md-flex justify-content-between align-items-center">
                <h3 class="card-title">
                    <i class="bi bi-building me-1"></i> {{ $room->name }}
                </h3>
                <div class="card-tools">
                    <a href="{{ route('admin.rooms.edit', $room) }}" class="btn btn-sm btn-warning">
                        <i class="bi bi-pencil me-1"></i> Edit
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-8">
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <th style="width: 200px;">Room Name</th>
                                    <td>{{ $room->name }}</td>
                                </tr>
                                <tr>
                                    <th>Room Number</th>
                                    <td><strong>{{ $room->room_number }}</strong></td>
                                </tr>
                                <tr>
                                    <th>Room Type</th>
                                    <td><span class="text-capitalize">{{ $room->room_type }}</span></td>
                                </tr>
                                <tr>
                                    <th>Floor</th>
                                    <td>{{ $room->floor ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Price per Night</th>
                                    <td><strong>Rs {{ number_format($room->price_per_night, 2) }}</strong></td>
                                </tr>
                                <tr>
                                    <th>Capacity</th>
                                    <td>{{ $room->capacity }} guest(s)</td>
                                </tr>
                                <tr>
                                    <th>Bed Count / Type</th>
                                    <td>{{ $room->bed_count }} {{ $room->bed_type ? '(' . ucfirst($room->bed_type) . ')' : '' }}</td>
                                </tr>
                                <tr>
                                    <th>Status</th>
                                    <td>
                                        @php
                                            $badgeClass = match($room->status) {
                                                'available' => 'success',
                                                'occupied' => 'warning',
                                                'maintenance' => 'danger',
                                                'reserved' => 'info',
                                                default => 'secondary'
                                            };
                                        @endphp
                                        <span class="badge bg-{{ $badgeClass }}">{{ ucfirst($room->status) }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Description</th>
                                    <td>{{ $room->description ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Notes</th>
                                    <td>{{ $room->notes ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Amenities</th>
                                    <td>
                                        @php
                                            $amenities = [];
                                            if ($room->has_ac) $amenities[] = 'AC';
                                            if ($room->has_tv) $amenities[] = 'TV';
                                            if ($room->has_wifi) $amenities[] = 'WiFi';
                                            if ($room->has_minibar) $amenities[] = 'Minibar';
                                            if ($room->has_balcony) $amenities[] = 'Balcony';
                                            if ($room->is_smoking_allowed) $amenities[] = 'Smoking';
                                            if ($room->is_wheelchair_accessible) $amenities[] = 'Wheelchair Accessible';
                                        @endphp
                                        @if (empty($amenities))
                                            <span class="text-muted">None</span>
                                        @else
                                            @foreach ($amenities as $amenity)
                                                <span class="badge bg-info me-1">{{ $amenity }}</span>
                                            @endforeach
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Created At</th>
                                    <td>{{ $room->created_at->format('d-m-Y H:i:s') }}</td>
                                </tr>
                                <tr>
                                    <th>Updated At</th>
                                    <td>{{ $room->updated_at->format('d-m-Y H:i:s') }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
