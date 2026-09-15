@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Digital Menu Design Settings</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('digital-menu.design.update') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <!-- Colors Section -->
                            <div class="col-md-6">
                                <h5 class="mb-3">Color Scheme</h5>
                                <div class="mb-3">
                                    <label class="form-label">Primary Color</label>
                                    <div class="input-group">
                                        <input type="color" class="form-control form-control-color" 
                                               name="primary_color" value="{{ $design->primary_color ?? '#dc2626' }}" 
                                               style="width: 50px;">
                                        <input type="text" class="form-control" 
                                               value="{{ $design->primary_color ?? '#dc2626' }}" readonly>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Secondary Color</label>
                                    <div class="input-group">
                                        <input type="color" class="form-control form-control-color" 
                                               name="secondary_color" value="{{ $design->secondary_color ?? '#1a1a2e' }}" 
                                               style="width: 50px;">
                                        <input type="text" class="form-control" 
                                               value="{{ $design->secondary_color ?? '#1a1a2e' }}" readonly>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Accent Color</label>
                                    <div class="input-group">
                                        <input type="color" class="form-control form-control-color" 
                                               name="accent_color" value="{{ $design->accent_color ?? '#f59e0b' }}" 
                                               style="width: 50px;">
                                        <input type="text" class="form-control" 
                                               value="{{ $design->accent_color ?? '#f59e0b' }}" readonly>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Background Color</label>
                                    <div class="input-group">
                                        <input type="color" class="form-control form-control-color" 
                                               name="background_color" value="{{ $design->background_color ?? '#f8f9fa' }}" 
                                               style="width: 50px;">
                                        <input type="text" class="form-control" 
                                               value="{{ $design->background_color ?? '#f8f9fa' }}" readonly>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Text Color</label>
                                    <div class="input-group">
                                        <input type="color" class="form-control form-control-color" 
                                               name="text_color" value="{{ $design->text_color ?? '#1f2937' }}" 
                                               style="width: 50px;">
                                        <input type="text" class="form-control" 
                                               value="{{ $design->text_color ?? '#1f2937' }}" readonly>
                                    </div>
                                </div>
                            </div>

                            <!-- Typography & Style Section -->
                            <div class="col-md-6">
                                <h5 class="mb-3">Typography & Style</h5>
                                <div class="mb-3">
                                    <label class="form-label">Font Family</label>
                                    <select class="form-select" name="font_family">
                                        <option value="Inter" {{ ($design->font_family ?? 'Inter') === 'Inter' ? 'selected' : '' }}>Inter</option>
                                        <option value="Poppins" {{ ($design->font_family ?? 'Inter') === 'Poppins' ? 'selected' : '' }}>Poppins</option>
                                        <option value="Roboto" {{ ($design->font_family ?? 'Inter') === 'Roboto' ? 'selected' : '' }}>Roboto</option>
                                        <option value="Open Sans" {{ ($design->font_family ?? 'Inter') === 'Open Sans' ? 'selected' : '' }}>Open Sans</option>
                                        <option value="Lato" {{ ($design->font_family ?? 'Inter') === 'Lato' ? 'selected' : '' }}>Lato</option>
                                        <option value="Playfair Display" {{ ($design->font_family ?? 'Inter') === 'Playfair Display' ? 'selected' : '' }}>Playfair Display</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Card Style</label>
                                    <select class="form-select" name="card_style">
                                        <option value="modern" {{ ($design->card_style ?? 'modern') === 'modern' ? 'selected' : '' }}>Modern</option>
                                        <option value="classic" {{ ($design->card_style ?? 'modern') === 'classic' ? 'selected' : '' }}>Classic</option>
                                        <option value="minimal" {{ ($design->card_style ?? 'modern') === 'minimal' ? 'selected' : '' }}>Minimal</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Layout</label>
                                    <select class="form-select" name="layout">
                                        <option value="grid" {{ ($design->layout ?? 'grid') === 'grid' ? 'selected' : '' }}>Grid</option>
                                        <option value="list" {{ ($design->layout ?? 'grid') === 'list' ? 'selected' : '' }}>List</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <hr class="my-4">

                        <!-- Display Options -->
                        <h5 class="mb-3">Display Options</h5>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" 
                                           name="show_categories" {{ $design->show_categories ?? true ? 'checked' : '' }}>
                                    <label class="form-check-label">Show Categories</label>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" 
                                           name="show_search" {{ $design->show_search ?? true ? 'checked' : '' }}>
                                    <label class="form-check-label">Show Search</label>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" 
                                           name="show_prices" {{ $design->show_prices ?? true ? 'checked' : '' }}>
                                    <label class="form-check-label">Show Prices</label>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" 
                                           name="show_images" {{ $design->show_images ?? true ? 'checked' : '' }}>
                                    <label class="form-check-label">Show Images</label>
                                </div>
                            </div>
                        </div>

                        <hr class="my-4">

                        <!-- Custom CSS -->
                        <div class="mb-3">
                            <label class="form-label">Custom CSS (Optional)</label>
                            <textarea class="form-control" name="custom_css" rows="5" placeholder="Add custom CSS rules...">{{ $design->custom_css ? json_encode($design->custom_css, JSON_PRETTY_PRINT) : '' }}</textarea>
                            <small class="text-muted">JSON format for custom CSS rules</small>
                        </div>

                        <!-- Preview Section -->
                        <div class="mb-3">
                            <h5 class="mb-3">Live Preview</h5>
                            <div id="designPreview" class="p-4 rounded border" style="background-color: {{ $design->background_color ?? '#f8f9fa' }};">
                                <div class="text-center mb-4" style="color: {{ $design->secondary_color ?? '#1a1a2e' }};">
                                    <h3 style="font-family: {{ $design->font_family ?? 'Inter' }};">Restaurant Name</h3>
                                    <p>Delicious food, exceptional service</p>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="card" style="border-radius: 16px; overflow: hidden; box-shadow: 0 4px 20px rgba(0,0,0,0.08);">
                                            <div style="height: 150px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);"></div>
                                            <div class="p-3">
                                                <h6 style="font-family: {{ $design->font_family ?? 'Inter' }}; color: {{ $design->text_color ?? '#1f2937' }};">Menu Item Name</h6>
                                                <p style="color: {{ $design->accent_color ?? '#f59e0b' }}; font-weight: 700;">Rs 250.00</p>
                                                <button class="btn btn-sm" style="background-color: {{ $design->primary_color ?? '#dc2626' }}; color: white;">Add to Cart</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="text-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Save Design Settings
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Live preview update
document.querySelectorAll('input[type="color"], select').forEach(input => {
    input.addEventListener('change', function() {
        updatePreview();
    });
});

function updatePreview() {
    const primaryColor = document.querySelector('[name="primary_color"]').value;
    const secondaryColor = document.querySelector('[name="secondary_color"]').value;
    const accentColor = document.querySelector('[name="accent_color"]').value;
    const backgroundColor = document.querySelector('[name="background_color"]').value;
    const textColor = document.querySelector('[name="text_color"]').value;
    const fontFamily = document.querySelector('[name="font_family"]').value;

    const preview = document.getElementById('designPreview');
    preview.style.backgroundColor = backgroundColor;
    preview.querySelector('h3').style.fontFamily = fontFamily;
    preview.querySelector('h3').style.color = secondaryColor;
    preview.querySelector('h6').style.fontFamily = fontFamily;
    preview.querySelector('h6').style.color = textColor;
    preview.querySelector('p').style.color = accentColor;
    preview.querySelector('button').style.backgroundColor = primaryColor;
}
</script>
@endsection
