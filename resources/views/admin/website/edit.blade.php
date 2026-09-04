@extends('admin.includes.main')
@section('content')

<div class="container-fluid">
    <br>
    <div class="mb-5">
        <h1 class="display-5 fw-bold text-dark mb-2">Site Settings</h1>
        <p class="text-muted">Manage your website configuration and preferences</p>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle me-2"></i>
            <strong>Please fix the following errors:</strong>
            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="mb-4">
        <ul class="nav nav-pills nav-fill bg-white rounded shadow-sm border p-1" id="settingsTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="identity-tab" data-bs-toggle="pill" data-bs-target="#identity" type="button" role="tab">
                    Site Identity
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="contact-tab" data-bs-toggle="pill" data-bs-target="#contact" type="button" role="tab">
                    Contact Info
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="social-tab" data-bs-toggle="pill" data-bs-target="#social" type="button" role="tab">
                    Social Media
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="seo-tab" data-bs-toggle="pill" data-bs-target="#seo" type="button" role="tab">
                    SEO Settings
                </button>
            </li>
            
        </ul>
    </div>

    <div class="tab-content" id="settingsTabContent">
        <div class="tab-pane fade show active" id="identity" role="tabpanel">
            <div class="card shadow-sm">
                <div class="card-body p-4">
                    <div class="mb-4">
                        <h2 class="h4 fw-semibold text-dark mb-2">Site Identity</h2>
                        <p class="text-muted">Configure your site's basic information and branding</p>
                    </div>

                    <form action="{{ route('admin.website.identity.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="row g-4">
                            <div class="col-md-6">
                                <label for="site_name" class="form-label fw-medium">
                                    Site Name <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control @error('site_name') is-invalid @enderror" 
                                       id="site_name" name="site_name" value="{{ old('site_name', $setting->site_name) }}" 
                                       required placeholder="Enter your site name">
                                @error('site_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="copyright" class="form-label fw-medium">
                                    Copyright <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control @error('copyright') is-invalid @enderror" 
                                       id="copyright" name="copyright" value="{{ old('copyright', $setting->copyright) }}" 
                                       required placeholder="Eg: 2025">
                                @error('copyright')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-12">
                                <label for="tagline" class="form-label fw-medium">Tagline</label>
                                <input type="text" class="form-control @error('tagline') is-invalid @enderror" 
                                       id="tagline" name="tagline" value="{{ old('tagline', $setting->tagline) }}" 
                                       placeholder="A brief description of your site">
                                @error('tagline')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row g-4 mt-2">
                            <div class="col-md-6">
                                <label for="logo_path" class="form-label fw-medium">Logo Upload</label>
                                <input type="file" class="form-control @error('logo_path') is-invalid @enderror" 
                                       id="logo_path" name="logo_path" accept="image/*">
                                @error('logo_path')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Recommended: PNG, JPG (max 2MB)</div>
                                @if($setting->getFirstMedia('logo'))
                                    <div class="mt-2">
                                        <small class="text-muted">Current logo:</small>
                                        <img src="{{ $setting->getFirstMediaUrl('logo') }}" alt="Current Logo" 
                                             class="img-thumbnail mt-1" style="max-width: 100px; max-height: 100px;">
                                        <div class="mt-1">
                                            <small class="text-muted">
                                                File: {{ $setting->getFirstMedia('logo')->name }} 
                                                ({{ number_format($setting->getFirstMedia('logo')->size / 1024, 2) }} KB)
                                            </small>
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <div class="col-md-6">
                                <label for="favicon_path" class="form-label fw-medium">Favicon Upload</label>
                                <input type="file" class="form-control @error('favicon_path') is-invalid @enderror" 
                                       id="favicon_path" name="favicon_path" accept="image/*">
                                @error('favicon_path')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Recommended: ICO, PNG 32x32px</div>
                                @if($setting->getFirstMedia('favicon'))
                                    <div class="mt-2">
                                        <small class="text-muted">Current favicon:</small>
                                        <img src="{{ $setting->getFirstMediaUrl('favicon') }}" alt="Current Favicon" 
                                             class="img-thumbnail mt-1" style="max-width: 32px; max-height: 32px;">
                                        <div class="mt-1">
                                            <small class="text-muted">
                                                File: {{ $setting->getFirstMedia('favicon')->name }} 
                                                ({{ number_format($setting->getFirstMedia('favicon')->size / 1024, 2) }} KB)
                                            </small>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="d-flex justify-content-end mt-4 pt-3">
                            <button type="submit" class="btn btn-primary px-4">Save Identity Settings</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="tab-pane fade" id="contact" role="tabpanel">
            <div class="card shadow-sm">
                <div class="card-body p-4">
                    <div class="mb-4">
                        <h2 class="h4 fw-semibold text-dark mb-2">Contact Information</h2>
                        <p class="text-muted">Manage your contact details and business address</p>
                    </div>

                    <form action="{{ route('admin.website.contact.update') }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="row g-4">
                            <div class="col-md-6">
                                <label for="contact_email" class="form-label fw-medium">Contact Email</label>
                                <input type="email" class="form-control @error('contact_email') is-invalid @enderror" 
                                       id="contact_email" name="contact_email" value="{{ old('contact_email', $setting->contact_email) }}" 
                                       placeholder="contact@example.com">
                                @error('contact_email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="contact_phone" class="form-label fw-medium">Contact Phone</label>
                                <input type="tel" class="form-control @error('contact_phone') is-invalid @enderror" 
                                       id="contact_phone" name="contact_phone" value="{{ old('contact_phone', $setting->contact_phone) }}" 
                                       placeholder="+1 (555) 123-4567">
                                @error('contact_phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mt-4">
                            <label for="address" class="form-label fw-medium">Business Address</label>
                            <textarea class="form-control @error('address') is-invalid @enderror" 
                                      id="address" name="address" rows="3" 
                                      placeholder="Enter your complete business address">{{ old('address', $setting->address) }}</textarea>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mt-4">
                            <label for="location" class="form-label fw-medium">Business Location</label>
                            <textarea class="form-control @error('location') is-invalid @enderror" 
                                      id="location" name="location" rows="3" 
                                      placeholder="<iframe>....</iframe>">{{ old('location', $setting->location) }}</textarea>
                            @error('location')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-end mt-4 pt-3">
                            <button type="submit" class="btn btn-primary px-4">Save Contact Information</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="tab-pane fade" id="social" role="tabpanel">
            <div class="card shadow-sm">
                <div class="card-body p-4">
                    <div class="mb-4">
                        <h2 class="h4 fw-semibold text-dark mb-2">Social Media Links</h2>
                        <p class="text-muted">Connect your social media profiles</p>
                    </div>

                    <form action="{{ route('admin.website.social.update') }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="row g-4">
                            <div class="col-md-6">
                                <label for="facebook_url" class="form-label fw-medium">Facebook URL</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-facebook"></i></span>
                                    <input type="url" class="form-control @error('facebook_url') is-invalid @enderror" 
                                           id="facebook_url" name="facebook_url" value="{{ old('facebook_url', $setting->facebook_url) }}" 
                                           placeholder="https://facebook.com/yourpage">
                                </div>
                                @error('facebook_url')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="twitter_url" class="form-label fw-medium">Twitter URL</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-twitter"></i></span>
                                    <input type="url" class="form-control @error('twitter_url') is-invalid @enderror" 
                                           id="twitter_url" name="twitter_url" value="{{ old('twitter_url', $setting->twitter_url) }}" 
                                           placeholder="https://twitter.com/yourusername">
                                </div>
                                @error('twitter_url')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row g-4 mt-2">
                            <div class="col-md-6">
                                <label for="instagram_url" class="form-label fw-medium">Instagram URL</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-instagram"></i></span>
                                    <input type="url" class="form-control @error('instagram_url') is-invalid @enderror" 
                                           id="instagram_url" name="instagram_url" value="{{ old('instagram_url', $setting->instagram_url) }}" 
                                           placeholder="https://instagram.com/yourusername">
                                </div>
                                @error('instagram_url')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="linkedin_url" class="form-label fw-medium">LinkedIn URL</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-linkedin"></i></span>
                                    <input type="url" class="form-control @error('linkedin_url') is-invalid @enderror" 
                                           id="linkedin_url" name="linkedin_url" value="{{ old('linkedin_url', $setting->linkedin_url) }}" 
                                           placeholder="https://linkedin.com/in/yourprofile">
                                </div>
                                @error('linkedin_url')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mt-4">
                            <label for="youtube_url" class="form-label fw-medium">YouTube URL</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-youtube"></i></span>
                                <input type="url" class="form-control @error('youtube_url') is-invalid @enderror" 
                                       id="youtube_url" name="youtube_url" value="{{ old('youtube_url', $setting->youtube_url) }}" 
                                       placeholder="https://youtube.com/c/yourchannel">
                            </div>
                            @error('youtube_url')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-end mt-4 pt-3">
                            <button type="submit" class="btn btn-primary px-4">Save Social Media Links</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="tab-pane fade" id="seo" role="tabpanel">
            <div class="card shadow-sm">
                <div class="card-body p-4">
                    <div class="mb-4">
                        <h2 class="h4 fw-semibold text-dark mb-2">SEO Settings</h2>
                        <p class="text-muted">Optimize your site for search engines</p>
                    </div>

                    <form action="{{ route('admin.website.seo.update') }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-4">
                            <label for="meta_title" class="form-label fw-medium">Meta Title</label>
                            <input type="text" class="form-control @error('meta_title') is-invalid @enderror" 
                                   id="meta_title" name="meta_title" maxlength="60" 
                                   value="{{ old('meta_title', $setting->meta_title) }}" 
                                   placeholder="Your site's title for search engines">
                            @error('meta_title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Recommended: 50-60 characters</div>
                        </div>

                        <div class="mb-4">
                            <label for="meta_description" class="form-label fw-medium">Meta Description</label>
                            <textarea class="form-control @error('meta_description') is-invalid @enderror" 
                                      id="meta_description" name="meta_description" rows="3" maxlength="160" 
                                      placeholder="A brief description of your site for search results">{{ old('meta_description', $setting->meta_description) }}</textarea>
                            @error('meta_description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Recommended: 150-160 characters</div>
                        </div>

                        <div class="mb-4">
                            <label for="meta_keywords" class="form-label fw-medium">Meta Keywords</label>
                            <textarea class="form-control @error('meta_keywords') is-invalid @enderror" 
                                      id="meta_keywords" name="meta_keywords" rows="2" 
                                      placeholder="keyword1, keyword2, keyword3">{{ old('meta_keywords', $setting->meta_keywords) }}</textarea>
                            @error('meta_keywords')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Separate keywords with commas</div>
                        </div>

                        <div class="d-flex justify-content-end mt-4 pt-3">
                            <button type="submit" class="btn btn-primary px-4">Save SEO Settings</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        
    </div>
</div>

@endsection
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const metaTitle = document.getElementById('meta_title');
        const metaDescription = document.getElementById('meta_description');

        if (metaTitle) {
            metaTitle.addEventListener('input', function() {
                const length = this.value.length;
                const counter = this.parentNode.querySelector('.form-text');
                if (length > 50) {
                    counter.classList.add('text-danger');
                    counter.classList.remove('text-muted');
                } else {
                    counter.classList.remove('text-danger');
                    counter.classList.add('text-muted');
                }
            });
        }

        if (metaDescription) {
            metaDescription.addEventListener('input', function() {
                const length = this.value.length;
                const counter = this.parentNode.querySelector('.form-text');
                if (length > 150) {
                    counter.classList.add('text-danger');
                    counter.classList.remove('text-muted');
                } else {
                    counter.classList.remove('text-danger');
                    counter.classList.add('text-muted');
                }
            });
        }
    });
</script>
    
@endpush