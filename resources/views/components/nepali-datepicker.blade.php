<link rel="stylesheet" href="{{ asset('assets/css/nepali.datepicker.v5.0.6.min.css') }}">

@props([
    'label' => null,
    'name' => 'dob', // Base name
    'value_ad' => '',
    'value_bs' => '',
    'required' => false,
    'class' => '',
])

@if ($label)
    <label class="form-label">{{ $label }}
        @if ($required)
            <span class="text-danger">*</span>
        @endif
    </label>
@endif

<div class="date-picker-wrapper">
    {{-- Nepali (BS) date input - First --}}
    <input 
        type="text" 
        name="{{ $name . '_bs' }}" 
        id="{{ $name }}-bs" 
        value="{{ old($name . '_bs', $value_bs) }}" 
        placeholder="YYYY-MM-DD (BS)" 
        class="form-control nepali-datepicker {{ $class }}" 
        {{ $required ? 'required' : '' }}
    >

    {{-- English (AD) date input - Second --}}
    <input 
        type="hidden" 
        name="{{ $name }}" 
        id="{{ $name }}-ad" 
        value="{{ old($name, $value_ad) }}" 
        placeholder="YYYY-MM-DD (AD)" 
        class="form-control ad-date {{ $class }}" 
        {{ $required ? 'required' : '' }}
         
    >
</div>

<script src="{{ asset('assets/js/nepali.datepicker.v5.0.6.min.js') }}"></script>

<script>
document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll(".nepali-datepicker").forEach(function (bsInput) {
        bsInput.nepaliDatePicker({
            dateFormat: 'YYYY-MM-DD',
            closeOnDateSelect: true,
            onSelect: function() {
                convertBStoAD(bsInput);
            }
        });

        bsInput.addEventListener('input', function() {
            convertBStoAD(bsInput);
        });

        bsInput.addEventListener('change', function() {
            convertBStoAD(bsInput);
        });
    });

    function convertBStoAD(bsInput) {
        const bsDate = bsInput.value.trim();
        
        if (bsDate && isValidBSDate(bsDate)) {
            try {
                const wrapper = bsInput.closest('.date-picker-wrapper');
                const adInput = wrapper ? wrapper.querySelector('.ad-date') : 
                                bsInput.parentElement.querySelector('.ad-date');

                if (adInput) {
                    const adDate = NepaliFunctions.BS2AD(bsDate);
                    adInput.value = adDate;
                } else {
                    console.error('AD input field not found');
                }
            } catch (e) {
                console.error("Error BS to AD:", bsDate, e);
            }
        } else if (!bsDate) {
            const wrapper = bsInput.closest('.date-picker-wrapper');
            const adInput = wrapper ? wrapper.querySelector('.ad-date') : 
                            bsInput.parentElement.querySelector('.ad-date');
            if (adInput) {
                adInput.value = '';
            }
        }
    }

    function isValidBSDate(dateString) {
        const regex = /^\d{4}-\d{2}-\d{2}$/;
        return regex.test(dateString);
    }
});
</script>