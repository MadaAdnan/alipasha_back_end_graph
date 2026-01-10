@props([
    'id' => null,
    'name' => '',
    'label' => '',
    'value' => null,
    'required' => false,
    'disabled' => false,
    'readonly' => false,
    'placeholder' => 'اختر...',
    'helpText' => '',
    'errorKey' => null,
    'wrapperClass' => '',
    'labelClass' => '',
    'selectClass' => '',
    'errorClass' => '',
    'options' => [],
    'key' => 'id',
    'display' => 'name',
    'multiple' => false,
])

@php

        $selectedValue = $value ?? old($name);
        $errorKey = $errorKey ?: $name;
        $hasError = $errors->has($errorKey);

        $selectId = $id ?: 'select-' . str_replace(['[', ']'], ['-', ''], $name);
        $wrapperClasses = trim("mb-3 {$wrapperClass}");
        $labelClasses = trim("form-label {$labelClass}");
        $selectClasses = trim("form-select" . ($hasError ? ' is-invalid' : '') . " {$selectClass}");
        $errorClasses = trim("invalid-feedback {$errorClass}");

        $options = is_null($options) ? [] :$options;

        // معالجة القيمة للمتعددة
        if ($multiple && !is_array($selectedValue)) {
            $selectedValue = $selectedValue ? explode(',', $selectedValue) : [];
        }
@endphp

<div class="{{ $wrapperClasses }}">
    @if($label)
        <label for="{{ $selectId }}" class="{{ $labelClasses }}">
            {{ $label }}
            @if($required)<span class="text-danger">*</span>@endif
        </label>
    @endif

    <select
        id="{{ $selectId }}"
        name="{{ $name }}{{ $multiple ? '[]' : '' }}"
        class="{{ $selectClasses }}"
        {{ $required ? 'required' : '' }}
        {{ $disabled ? 'disabled' : '' }}
        {{ $readonly ? 'readonly' : '' }}
        {{ $multiple ? 'multiple' : '' }}
        {{ $attributes }}
    >
        @if(!$multiple && $placeholder)
            <option value="" disabled {{ !$selectedValue ? 'selected' : '' }}>
                {{ $placeholder }}
            </option>
        @endif

        @foreach($options as $option)
            @php
                $optionValue = is_array($option) ?
                    (isset($option[$key]) ? $option[$key] : (isset($option['id']) ? $option['id'] : null)) :
                    $option;

                $optionDisplay = is_array($option) ?
                    (isset($option[$display]) ? $option[$display] : (isset($option['name']) ? $option['name'] : $optionValue)) :
                    $option;

                $isSelected = $multiple ?
                    in_array((string)$optionValue, $selectedValue) :
                    ((string)$optionValue === (string)$selectedValue);
            @endphp

            @if(!is_null($optionValue))
                <option value="{{ $optionValue }}" {{ $isSelected ? 'selected' : '' }}>
                    {{ $optionDisplay }}
                </option>
            @endif
        @endforeach
    </select>

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
