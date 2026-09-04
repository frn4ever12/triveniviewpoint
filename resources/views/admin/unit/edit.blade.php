@extends('admin.includes.main')

@section('title', 'Edit Unit')

@section('content')
    <div class="container-fluid">
        <x-breadcrumb title="Edit Unit" route="admin.units.index" button="Back to List" icon="bi-arrow-left" />

                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('admin.units.update', $unit) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="row g-3">
                                <div class="col-md-6 col-sm-12">

                                    <label for="name" class="form-label">Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"
                                        name="name" value="{{ old('name', $unit->name) }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>                             

                                <div class="col-md-6 col-sm-12">
                                    <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                                    <select class="form-select @error('status') is-invalid @enderror" id="status"
                                        name="status" required>
                                        @foreach( \App\Enums\CommonStatusEnum::cases() as $status)
                                            <option value="{{ $status->value }}" {{ old('status', $unit->status) == $status->value ? 'selected' : '' }}>
                                                {{ $status->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>                               
                            </div>

                            <div class="mt-4">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Update Unit
                                </button>
                                <a href="{{ route('admin.units.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-times"></i> Cancel
                                </a>

                            </div>
                        </form>
                    </div>
                </div>
            </div>      
@endsection
