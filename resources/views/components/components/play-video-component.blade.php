@props([
    'product' => null,
])
<div class="video-widget-container">
    <div class="video-widget-wrapper">
        <!-- صورة المنتج -->
        <img src="{{ $product->getImage() }}"
             alt="صورة المنتج"
             class="video-widget-image"
             onerror="this.src='{{ asset('images/noImage.jpeg') }}'; this.classList.add('video-no-image');">

        <!-- زر مشاهدة الفيديو -->
        <a href="{{ $product->video }}" target="_blank" class="video-play-btn" title="مشاهدة الفيديو">
            <i class="fa fa-play"></i>
        </a>
    </div>
</div>

<style>
    .video-widget-container {
        width: 100%;
        height: 100%;
    }

    .video-widget-wrapper {
        position: relative;
        width: 100%;
        height: 100%;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .video-widget-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }

    .video-play-btn {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 60px;
        height: 60px;
        background: linear-gradient(135deg, var(--red-accent) 0%, var(--red) 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 24px;
        text-decoration: none;
        transition: all 0.3s ease;
        box-shadow: 0 4px 12px rgba(227, 6, 19, 0.3);
        z-index: 10;
    }

    .video-play-btn:hover {
        transform: translate(-50%, -50%) scale(1.15);
        box-shadow: 0 8px 20px rgba(227, 6, 19, 0.4);
    }

    .video-play-btn:active {
        transform: translate(-50%, -50%) scale(0.95);
    }

    .video-widget-wrapper::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.3);
        opacity: 0;
        transition: opacity 0.3s ease;
        z-index: 5;
    }

    .video-widget-wrapper:hover::before {
        opacity: 1;
    }

    .video-widget-image.video-no-image {
        background: linear-gradient(135deg, #f5f6fa 0%, #e9ecef 100%);
        object-fit: contain;
        padding: 20px;
    }
</style>
