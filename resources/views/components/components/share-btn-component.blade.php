

@props([
    'url' => request()->url(),
    'title' => '',
    'description' => '',
    'image' => '',
    'showLabel' => true,
    'platforms' => ['facebook', 'twitter', 'whatsapp', 'telegram', 'linkedin']
])

@php
    $encodedUrl = urlencode($url);
    $encodedTitle = urlencode($title);
    $encodedDescription = urlencode($description);
    $encodedImage = urlencode($image);

    $shareLinks = [
        'facebook' => "https://www.facebook.com/sharer/sharer.php?u={$encodedUrl}",
        'twitter' => "https://twitter.com/intent/tweet?url={$encodedUrl}&text={$encodedTitle}",
        'whatsapp' => "https://wa.me/?text={$encodedTitle}%20{$encodedUrl}",
        'telegram' => "https://t.me/share/url?url={$encodedUrl}&text={$encodedTitle}",
        'linkedin' => "https://www.linkedin.com/sharing/share-offsite/?url={$encodedUrl}",
        'email' => "mailto:?subject={$encodedTitle}&body={$encodedDescription}%20{$encodedUrl}"
    ];

    $platformData = [
        'facebook' => ['name' => 'Facebook', 'icon' => 'fab fa-facebook-f', 'color' => '#1877F2'],
        'twitter' => ['name' => 'Twitter', 'icon' => 'fab fa-x-twitter', 'color' => '#000000'],
        'whatsapp' => ['name' => 'WhatsApp', 'icon' => 'fab fa-whatsapp', 'color' => '#25D366'],
        'telegram' => ['name' => 'Telegram', 'icon' => 'fab fa-telegram-plane', 'color' => '#0088cc'],
        'linkedin' => ['name' => 'LinkedIn', 'icon' => 'fab fa-linkedin-in', 'color' => '#0A66C2'],
        'email' => ['name' => 'Email', 'icon' => 'fas fa-envelope', 'color' => '#EA4335']
    ];
@endphp

<div {{ $attributes->merge(['class' => 'd-inline-block']) }}>
    <div class="dropdown">
        <button
            class="btn btn-success btn-share d-flex align-items-center gap-2 shadow-sm"
            type="button"
            id="shareDropdown{{ uniqid() }}"
            data-bs-toggle="dropdown"
            aria-expanded="false"
        >
            <i class="fas fa-share-alt"></i>
            @if($showLabel)
                <span>مشاركة</span>
            @endif
        </button>

        <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 share-menu" aria-labelledby="shareDropdown{{ uniqid() }}">
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
                    class="dropdown-item d-flex align-items-center py-2 share-item"
                    onclick="copyToClipboard('{{ $url }}', this)"
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

<style>
    .btn-share {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        border: none;
        border-radius: 8px;
        padding: 10px 20px;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .btn-share:hover {
        background: linear-gradient(135deg, #059669 0%, #047857 100%);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3) !important;
    }

    .btn-share:active {
        transform: translateY(0);
    }

    .share-menu {
        min-width: 280px;
        border-radius: 12px;
        padding: 8px 0;
        animation: slideDown 0.3s ease;
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

<script>
    function copyToClipboard(text, button) {
        navigator.clipboard.writeText(text).then(() => {
            const copyText = button.querySelector('.copy-text');
            const icon = button.querySelector('i');
            const originalText = copyText.textContent;

            // تغيير النص والأيقونة
            copyText.textContent = 'تم النسخ ✓';
            copyText.classList.add('text-success');
            icon.className = 'fas fa-check text-success';

            // إعادة النص الأصلي بعد ثانيتين
            setTimeout(() => {
                copyText.textContent = originalText;
                copyText.classList.remove('text-success');
                icon.className = 'fas fa-copy text-secondary';
            }, 2000);
        }).catch(err => {
            console.error('فشل النسخ:', err);
            alert('حدث خطأ أثناء النسخ');
        });
    }
</script>
