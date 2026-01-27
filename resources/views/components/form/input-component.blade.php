@props([
    'id' => null,
    'name' => '',
    'label' => '',
    'type' => 'text',
    'value' =>null,
    'required' => false,
    'disabled' => false,
    'readonly' => false,
    'placeholder' => '',
    'helpText' => '',
    'goto' => '',
    'errorKey' => null, // يمكن استخدامه لتجاوز اسم الحقل للرسائل
    'addonBefore' => '',
    'addonAfter' => '',
    'wrapperClass' => '',
    'labelClass' => '',
    'inputClass' => '',
    'errorClass' => '',
])

@php
    $inputValue = $value ?? old($name);
      $errorKey = $errorKey ?: $name;
      $hasError = $errors->has($errorKey);
      $inputId = $id ?: 'input-' . str_replace(['[', ']'], ['-', ''], $name);

      // دمج الفئات مع الفئات الافتراضية
      $wrapperClasses = trim("mb-3 {$wrapperClass}");
      $labelClasses = trim("form-label {$labelClass}");
      $inputClasses = trim("form-control" . ($hasError ? ' is-invalid' : '') . " {$inputClass}");
      $errorClasses = trim("invalid-feedback d-inline-block {$errorClass}");

      // إدارة السمات
      $attributes = $attributes->merge([
          'id' => $inputId,
          'name' => $name,
          'type' => $type,
          'value' => $inputValue,
          'placeholder' => $placeholder,
          'class' => $inputClasses,
          'disabled' => $disabled,
          'readonly' => $readonly,
      ]);

      if ($required) {
          $attributes = $attributes->merge(['required' => 'required']);
      }

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

    <div class="input-group">
        @if($addonBefore)
            <span class="input-group-text">{!! $addonBefore !!}</span>
        @endif

        <input {{ $attributes }}>

        @if($addonAfter)
            <span class="input-group-text">{!! $addonAfter !!}</span>
        @endif
    </div>

    @if($helpText)
        <div class="form-text text-red-accent">
            @if($goto!=null)
                {{$helpText}}
                <a class="text-muted fs-6 " href="{{$goto??'#'}}"><sub>اضغط هنا</sub></a>
            @else
                {{$helpText}}
            @endif
        </div>
    @endif

    @if($hasError)

        @foreach($errors->get($errorKey) as $message)

            <div class="{{ $errorClasses }}">
                {{ $message }}
            </div>
        @endforeach
    @endif
</div>
