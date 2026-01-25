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
    'showStrength' => false,
    'toggleVisibility' => true,
    'isRounded'=>false
])

@php
    // تحديد القيمة
    $inputValue = $value ?? old($name);

    $errorKey = $errorKey ?: $name;
    $hasError = $errors->has($errorKey);
    $inputId = $id ?: 'password-' . str_replace(['[', ']'], ['-', ''], $name);

    // دمج الفئات
    $wrapperClasses = trim("mb-3 password-wrapper {$wrapperClass}");
    $labelClasses = trim("form-label {$labelClass}");
    $inputClasses = trim("form-control password-input" . ($hasError ? ' is-invalid' : '') . " {$inputClass}");
    if($isRounded){
        $inputClasses.=' rounded';
    }
    $errorClasses = trim("invalid-feedback {$errorClass}");

    // إدارة السمات
    $attributes = $attributes->merge([
        'id' => $inputId,
        'name' => $name,
        'type' => 'password',
        'value' => $inputValue,
        'placeholder' => $placeholder,
        'class' => $inputClasses,
        'disabled' => $disabled,
        'readonly' => $readonly,
        'autocomplete' => 'new-password',
    ]);

    if ($required) {
        $attributes = $attributes->merge(['required' => 'required']);
    }
@endphp

<div class="{{ $wrapperClasses }}" id="wrapper-{{ $inputId }}">
    @if($label)
        <label for="{{ $inputId }}" class="{{ $labelClasses }}">
            {{ $label }}
            @if($required)
                <span class="text-danger">*</span>
            @endif
        </label>
    @endif

    <div class="input-group position-relative">
        <input {{ $attributes }}>

        @if($toggleVisibility)
            <button type="button"
                    id="toggle-{{ $inputId }}"
                    class="btn btn-outline-secondary password-toggle position-absolute  transparent "
                    style="left:5px;z-index: 100 "
                    data-target="#{{ $inputId }}"
                    aria-label="إظهار/إخفاء كلمة المرور">
                <i class="fa fa-eye-slash text-gray"></i>
            </button>
        @endif
    </div>

    @if($helpText)
        <div class="form-text">{{ $helpText }}</div>
    @endif

    @if($showStrength)
        <div class="password-strength mt-2">
            <div class="progress" style="height: 5px;">
                <div class="progress-bar" role="progressbar" style="width: 0%"></div>
            </div>
            <small class="password-strength-text text-muted"></small>
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

{{-- JavaScript مباشر بدون @push --}}
<script>
    (function() {
        // انتظر حتى يتم تحميل DOM
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initPasswordToggles);
        } else {
            initPasswordToggles();
        }

        function initPasswordToggles() {
            console.log('تهيئة أزرار إظهار كلمة المرور...');

            const toggleButtons = document.querySelectorAll('.password-toggle');
            console.log('عدد الأزرار الموجودة:', toggleButtons.length);

            toggleButtons.forEach((button, index) => {
                console.log(`زر ${index + 1}:`, button);

                // إزالة أي معالج حدث سابق
                button.replaceWith(button.cloneNode(true));
            });

            // إعادة تحديد الأزرار بعد الاستبدال
            document.querySelectorAll('.password-toggle').forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    console.log('تم النقر على زر التبديل');

                    const targetId = this.getAttribute('data-target');
                    console.log('الهدف:', targetId);

                    const input = document.querySelector(targetId);
                    console.log('حقل الإدخال:', input);

                    if (!input) {
                        console.error('لم يتم العثور على حقل الإدخال:', targetId);
                        return;
                    }

                    const icon = this.querySelector('i');
                    console.log('الأيقونة:', icon);

                    if (input.type === 'password') {
                        input.type = 'text';
                        icon.classList.remove('fa-eye');
                        icon.classList.add('fa-eye-slash');
                        this.setAttribute('aria-label', 'إخفاء كلمة المرور');
                        console.log('تم تغيير النوع إلى text');
                    } else {
                        input.type = 'password';
                        icon.classList.remove('fa-eye-slash');
                        icon.classList.add('fa-eye');
                        this.setAttribute('aria-label', 'إظهار كلمة المرور');
                        console.log('تم تغيير النوع إلى password');
                    }
                });

                // إضافة مؤشر تفاعل
                button.style.cursor = 'pointer';
            });
        }
    })();
</script>
