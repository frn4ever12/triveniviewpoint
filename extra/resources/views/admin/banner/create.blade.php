@extends('admin.includes.main')

@section('title', 'Create Banner')

@section('content')
    <div class="container-fluid">
        <x-breadcrumb title="Create New Banner" route="admin.banners.index" button="Back to List" icon="bi-arrow-left" />

        <div class="card">
            <div class="card-body">
                <form action="{{ route('admin.banners.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    
                    <div class="row g-3">
                        <div class="col-md-6 col-sm-12">
                            <label for="title" class="form-label">Banner Title</label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror" id="title"
                                name="title" value="{{ old('title') }}" required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                      
                        <div class="col-md-6 col-sm-12">
                            <label for="priority" class="form-label">Priority</label>
                            <input type="text" class="form-control @error('priority') is-invalid @enderror" id="priority"
                                name="priority" value="{{ old('priority') }}" required>
                            @error('priority')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 col-sm-12">
                            <label for="image" class="form-label">Banner Image<span  class="text-danger">*</span></label>
                            <input type="file" class="form-control @error('image') is-invalid @enderror" id="image"
                                name="image" accept="image/*">
                            <div class="form-text">Max size: 2MB. Recommended: 800x600px</div>
                            @error('image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>                 

                   

                    <div class="row g-3 mt-2">
                        <div class="col-md-6 col-sm-12">
                            <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                            <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                                @foreach (\App\Enums\CommonStatusEnum::cases() as $status)
                                    <option value="{{ $status->value }}"
                                        {{ old('status') == $status->value ? 'selected' : '' }}>
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
                            <i class="bi bi-save me-2"></i> Create Banner
                        </button>
                        <a href="{{ route('admin.banners.index') }}" class="btn btn-danger">
                            <i class="bi bi-x-lg me-2"></i> Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
