@extends('admin.includes.main')

@section('title', 'About Section')

@section('content')

    <div class="container-fluid">
        <x-breadcrumb title="About Us" route="dashboard" button="Dashboard" icon="bi-arrow-left" />

        <div class="card">
            <div class="card-body">
                @if (isset($about))
                    <form action="{{ route('admin.abouts.update', $about->id) }}" method="POST" enctype="multipart/form-data">
                        @method('PUT')
                    @else
                        <form action="{{ route('admin.abouts.store') }}" method="POST" enctype="multipart/form-data">
                @endif
                @csrf

                <div class="row g-3">
                    <!-- Title -->
                    <div class="col-md-6 col-sm-12">
                        <label for="title" class="form-label">Title <span class="text-danger">*</span></label>
                        <input type="text" id="title" name="title"
                            class="form-control @error('title') is-invalid @enderror"
                            value="{{ old('title', $about->title ?? '') }}" required>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Image -->
                    <div class="col-md-6 col-sm-12">
                        <label for="image" class="form-label">Image</label>
                        <input type="file" id="image" name="image"
                            class="form-control @error('image') is-invalid @enderror"
                            accept="image/>
                                    <div class="form-text">Max size:
                        2MB. Recommended: 800x600px
                    </div>
                    @error('image')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror

                    @if (isset($about) && $about->getFirstMediaUrl('image'))
                        <div class="mt-2">
                            <img src="{{ $about->getFirstMediaUrl('image') }}" alt="About Image"
                                style="max-height: 150px; max-width: 100%;">
                        </div>
                    @endif
                    <!-- Description -->
                    <div class="col-12">
                        <label for="description" class="form-label">Description <span class="text-danger">*</span></label>
                        <x-text-editor
                            name="description">{{ old('description', $about->description ?? '') }}</x-text-editor>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <!-- Status -->
                    <div class="col-md-6 col-sm-12">
                        <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                        <select name="status" id="status" class="form-select @error('status') is-invalid @enderror"
                            required>
                            <option value="active" {{ old('status', $about->status ?? '') == 'active' ? 'selected' : '' }}>
                                Active</option>
                            <option value="inactive"
                                {{ old('status', $about->status ?? '') == 'inactive' ? 'selected' : '' }}>
                                Inactive
                            </option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-2"></i> {{ isset($about) ? 'Update About' : 'Save About' }}
                        </button>

                    </div>



                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
