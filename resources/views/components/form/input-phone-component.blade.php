@props([
    'id' => null,
    'name' => '',
    'label' => '',
    'value' => null,
    'required' => false,
    'disabled' => false,
    'readonly' => false,
    'placeholder' => '',
    'helpText' => '',
    'errorKey' => null,
    'wrapperClass' => '',
    'labelClass' => '',
    'inputClass' => '',
    'errorClass' => '',
    'countryName' => 'country_code',
    'countryValue' => 'إختر دولتك',

    'showFlag' => true,
])

@php

    $phoneValue = $value ?? old($name);
    $countryValue = old($countryName) ?? $countryValue;

    $errorKey = $errorKey ?: $name;
    $hasError = $errors->has($errorKey);

    $inputId = $id ?: 'phone-' . str_replace(['[', ']'], ['-', ''], $name);
    $countryId = 'country-' . $inputId;

    $wrapperClasses = trim("mb-3 {$wrapperClass}");
    $labelClasses = trim("form-label {$labelClass}");
    $inputClasses = trim("form-control" . ($hasError ? ' is-invalid' : '') . " {$inputClass}");
    $errorClasses = trim("invalid-feedback d-inline-block {$errorClass}");


@endphp

<div class="{{ $wrapperClasses }}">
    @if($label)
        <label for="{{ $inputId }}" class="{{ $labelClasses }}">
            {{ $label }}
            @if($required)
                <span class="text-danger">*</span>
            @endif
        </label>
    @endif

    <div class="input-group ltr">

        {{-- حقل رقم الهاتف --}}
        <input
            type="tel"
            id="{{ $inputId }}"
            name="{{ $name }}"
            value="{{ $phoneValue }}"
            class="{{ $inputClasses }}"
            placeholder="{{ $placeholder ?: '5XXXXXXXX' }}"
            {{ $required ? 'required' : '' }}
            {{ $disabled ? 'disabled' : '' }}
            {{ $readonly ? 'readonly' : '' }}
            {{ $attributes }}
        >
        {{-- اختيار الدولة --}}
        @if(count($countries) > 0)
            <select
                name="{{ $countryName }}"
                id="{{ $countryId }}"
                class="form-select"
                style="max-width: 160px;"
                {{ $disabled ? 'disabled' : '' }}
                {{ $required ? 'required' : '' }}
            >
                @foreach($countries as  $country)
                    @php
                        $countryData = is_array($country) ? $country : [
                            'name' => $country->name,
                            'id' => $country->id,
                            'code' => $country->code
                        ];



                        $displayText = $countryData['code'] . ' ' . $countryData['name'];
                    @endphp

                    <option value="{{ $countryData['code'] }}"
                        {{ $countryValue == $countryData['code'] ? 'selected' : '' }}>
                        {{ $displayText }}
                    </option>
                @endforeach
            </select>
        @else
            <input type="text"
                   name="{{ $countryName }}"
                   value="{{ $countryValue }}"
                   class="form-control"
                   style="max-width: 80px;"
                   placeholder="+963"
                   maxlength="5"
                {{ $required ? 'required' : '' }}>
        @endif

    </div>

    @if($helpText)
        <div class="form-text">{{ $helpText }}</div>
    @endif

    @if($hasError)
        @foreach($errors->get($errorKey) as $message)
            <div class="{{ $errorClasses }}">
                {{ $message }}
            </div>
        @endforeach
    @endif
</div>
