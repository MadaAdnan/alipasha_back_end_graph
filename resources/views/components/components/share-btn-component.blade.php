
@props([
    'url' => null,
    'title' => '',
    'description' => '',
    'image' => '',
    'showLabel' => true,
    'platforms' => null
])

@php
    // Use the component properties or defaults
    $url = $url ?? request()->url();
    $platforms = $platforms ?? ['facebook', 'twitter', 'whatsapp', 'telegram', 'linkedin'];
    
    $encodedUrl = urlencode($url);
    $encodedTitle = urlencode($title);
    $encodedDescription = urlencode($description);
    $encodedImage = urlencode($image);

    $shareLinks = [
        'facebook' => "https://www.facebook.com/sharer/sharer.php?u={$encodedUrl}",
        'twitter' => "https://twitter.com/intent/tweet?url={$encodedUrl}&text={$encodedTitle}",
        'whatsapp' => "https://wa.me/send?text={$encodedTitle}%20{$encodedUrl}",
        'telegram' => "https://t.me/share/url?url={$encodedUrl}&text={$encodedTitle}",
        'linkedin' => "https://www.linkedin.com/sharing/share-offsite/?url={$encodedUrl}",
        'email' => "mailto:?subject={$encodedTitle}&body=" . urlencode($encodedDescription . ' ' . $url)
    ];

    $platformData = [
        'facebook' => ['name' => 'Facebook', 'icon' => 'fab fa-facebook-f', 'color' => '#1877F2'],
        'twitter' => ['name' => 'Twitter', 'icon' => 'fab fa-x-twitter', 'color' => '#000000'],
        'whatsapp' => ['name' => 'WhatsApp', 'icon' => 'fab fa-whatsapp', 'color' => '#25D366'],
        'telegram' => ['name' => 'Telegram', 'icon' => 'fab fa-telegram-plane', 'color' => '#0088cc'],
        'linkedin' => ['name' => 'LinkedIn', 'icon' => 'fab fa-linkedin-in', 'color' => '#0A66C2'],
        'email' => ['name' => 'Email', 'icon' => 'fas fa-envelope', 'color' => '#EA4335']
    ];

    // إنشاء ID فريد لكل زر
    $uniqueId = 'shareBtn_' . uniqid() . '_' . rand(1000, 9999);
@endphp

<div {{ $attributes->merge(['class' => 'd-inline-block']) }}>
    <div class="dropdown">
        <button
            class="btn btn-success btn-share d-flex align-items-center gap-2 shadow-sm"
            type="button"
            id="{{ $uniqueId }}"
            data-bs-toggle="dropdown"
            aria-expanded="false"
        >
            <i class="fas fa-share-alt"></i>
            @if($showLabel)
                <span>مشاركة</span>
            @endif
        </button>

        <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 share-menu" aria-labelledby="{{ $uniqueId }}">
            <li class="dropdown-header bg-light">
                <i class="fas fa-share-nodes me-2"></i>
                <strong>مشاركة عبر</strong>
            </li>
            <li><hr class="dropdown-divider"></li>

            @foreach($platforms as $platform)
                @if(isset($shareLinks[$platform]) && isset($platformData[$platform]))
                    <li>
                        <a
                            class="dropdown-item d-flex align-items-center py-2 share-item"
                            href="{{ $shareLinks[$platform] }}"
                            target="_blank"
                            rel="noopener noreferrer"
                            data-platform="{{ $platform }}"
                        >
                            <span class="share-icon me-3 d-flex align-items-center justify-content-center rounded-circle"
                                  style="background-color: {{ $platformData[$platform]['color'] }}15;">
                                <i class="{{ $platformData[$platform]['icon'] }}"
                                   style="color: {{ $platformData[$platform]['color'] }};"></i>
                            </span>
                            <span class="fw-medium">{{ $platformData[$platform]['name'] }}</span>
                        </a>
                    </li>
                @endif
            @endforeach

            <li><hr class="dropdown-divider"></li>
            <li>
                <button
                    type="button"
                    class="dropdown-item d-flex align-items-center py-2 share-item border-0 bg-transparent w-100 text-start"
                    onclick="copyShareLink('{{ addslashes($url) }}', this)"
                    id="copy-link-btn-{{ $uniqueId }}"
                >
                    <span class="share-icon me-3 d-flex align-items-center justify-content-center rounded-circle bg-light">
                        <i class="fas fa-copy text-secondary"></i>
                    </span>
                    <span class="fw-medium copy-text">نسخ الرابط</span>
                </button>
            </li>
        </ul>
    </div>
</div>

@once
    @push('styles')
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
        <style>
            .btn-share {
                background: linear-gradient(135deg, #10b981 0%, #059669 100%);
                border: none;
                border-radius: 8px;
                padding: 10px 20px;
                font-weight: 600;
                transition: all 0.3s ease;
                color: white;
            }

            .btn-share:hover {
                background: linear-gradient(135deg, #059669 0%, #047857 100%);
                transform: translateY(-2px);
                box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3) !important;
                color: white;
            }

            .btn-share:active {
                transform: translateY(0);
            }

            .share-menu {
                min-width: 280px;
                border-radius: 12px;
                padding: 8px 0;
                animation: slideDown 0.3s ease;
                z-index: 10000;
            }

            @keyframes slideDown {
                from {
                    opacity: 0;
                    transform: translateY(-10px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            .dropdown-header {
                padding: 12px 16px;
                border-radius: 8px 8px 0 0;
                margin-bottom: 0;
            }

            .share-item {
                transition: all 0.2s ease;
                padding-right: 16px !important;
                padding-left: 16px !important;
                cursor: pointer;
            }

            .share-item:hover {
                background: linear-gradient(90deg, #f0fdf4 0%, #d1fae5 100%);
                padding-right: 20px !important;
            }

            .share-icon {
                width: 40px;
                height: 40px;
                font-size: 18px;
                transition: all 0.3s ease;
            }

            .share-item:hover .share-icon {
                transform: scale(1.1);
            }

            .dropdown-divider {
                margin: 8px 0;
                opacity: 0.1;
            }

            .copy-text {
                transition: color 0.3s ease;
            }
        </style>
    @endpush

    @push('js')
        <script>
            // Check if component script is already loaded to avoid conflicts
            if (typeof window.LaravelShareComponent === 'undefined') {
                window.LaravelShareComponent = true;
                
                function copyShareLink(text, button) {
                    // التأكد من أن المتصفح يدعم Clipboard API
                    if (navigator.clipboard && window.isSecureContext) {
                        // استخدام Clipboard API الحديث
                        navigator.clipboard.writeText(text).then(() => {
                            showCopySuccess(button);
                        }).catch(err => {
                            console.error('فشل النسخ:', err);
                            fallbackCopy(text, button);
                        });
                    } else {
                        // استخدام الطريقة القديمة
                        fallbackCopy(text, button);
                    }
                }

                function fallbackCopy(text, button) {
                    try {
                        const textArea = document.createElement("textarea");
                        textArea.value = text;
                        textArea.style.position = "fixed";
                        textArea.style.left = "-999999px";
                        textArea.style.top = "0";
                        textArea.style.opacity = "0";
                        textArea.style.pointerEvents = "none";
                        document.body.appendChild(textArea);
                        textArea.focus();
                        textArea.select();

                        const successful = document.execCommand('copy');
                        if (successful) {
                            showCopySuccess(button);
                        } else {
                            console.warn('execCommand failed, showing fallback message');
                            showCopySuccess(button, 'تم النسخ!');
                        }
                    } catch (err) {
                        console.error('Fallback copy failed:', err);
                        showCopySuccess(button, 'تم النسخ!');
                        // Fallback to showing a temporary message
                        const tempInput = document.createElement('input');
                        tempInput.value = text;
                        document.body.appendChild(tempInput);
                        tempInput.select();
                        document.execCommand('copy');
                        document.body.removeChild(tempInput);
                    }
                }

                function showCopySuccess(button, successMessage = 'تم النسخ ✓') {
                    const copyText = button.querySelector('.copy-text');
                    const icon = button.querySelector('i');

                    if (!copyText || !icon) return;

                    const originalText = copyText.textContent;
                    const originalIconClass = icon.className;

                    // تغيير النص والأيقونة
                    copyText.textContent = successMessage;
                    copyText.classList.add('text-success');
                    icon.className = 'fas fa-check text-success';

                    // إعادة النص الأصلي بعد ثانيتين
                    setTimeout(() => {
                        copyText.textContent = originalText;
                        copyText.classList.remove('text-success');
                        icon.className = originalIconClass;
                    }, 2000);
                }
                
                // Add event listeners to share links to track clicks
                document.addEventListener('click', function(e) {
                    const shareLink = e.target.closest('[data-platform]');
                    if (shareLink) {
                        // Optional: Track sharing events
                        console.log('Sharing via:', shareLink.getAttribute('data-platform'));
                    }
                });
            }
        </script>
    @endpush
@endonce
