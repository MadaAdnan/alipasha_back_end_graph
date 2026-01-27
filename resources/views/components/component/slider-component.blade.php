@props([
    'id' => 'main-slider',
    'items' => [],
])

@php
    $carouselId = 'carousel-' . $id;
@endphp

<div class="slider-wrapper">
    <div class="slider-container">

        {{-- Thumbnails --}}
        @if(count($items) > 1)
            <div class="slider-thumbnails-vertical">
                @foreach($items as $index => $item)
                    <div class="thumbnail-item {{ $loop->first ? 'active' : '' }}"
                         data-index="{{ $index }}">
                        <img src="{{ $item }}"
                             alt="صورة {{ $index + 1 }}"
                             onerror="this.src='{{ asset('images/noImage.jpeg') }}'">
                    </div>
                @endforeach
            </div>
        @endif

        {{-- Main Slider --}}
        <div id="{{ $carouselId }}"
             class="carousel slide slider-component rounded"
             data-bs-ride="carousel">

            <div class="carousel-inner">
                @forelse($items as $item)
                    <div class="carousel-item {{ $loop->first ? 'active' : '' }}">
                        <img src="{{ $item }}"
                             class="d-block w-100 carousel-img rounded slider-image"
                             alt="صورة المنتج"
                             data-bs-toggle="modal"
                             data-bs-target="#imageModal"
                             onclick="openImagePreview(this.src)"
                             onerror="this.src='{{ asset('images/noImage.jpeg') }}'; this.classList.add('no-image-placeholder');">
                    </div>
                @empty
                    <div class="carousel-item active">
                        <div class="no-image-container">
                            <img src="{{ asset('images/noImage.jpeg') }}"
                                 class="d-block w-100 carousel-img rounded no-image-placeholder"
                                 alt="لا توجد صورة">
                        </div>
                    </div>
                @endforelse
            </div>

            {{-- Controls --}}
            @if(count($items) > 1)
                <button class="carousel-control-prev"
                        type="button"
                        data-bs-target="#{{ $carouselId }}"
                        data-bs-slide="prev">
                    <span class="carousel-control-prev-icon"></span>
                </button>

                <button class="carousel-control-next"
                        type="button"
                        data-bs-target="#{{ $carouselId }}"
                        data-bs-slide="next">
                    <span class="carousel-control-next-icon"></span>
                </button>
            @endif

            {{-- Zoom --}}
            <button class="slider-zoom-btn"
                    data-bs-toggle="modal"
                    data-bs-target="#imageModal"
                    onclick="openImagePreview(document.querySelector('#{{ $carouselId }} .carousel-item.active img').src)"
                    title="معاينة الصورة">
                <i class="fas fa-expand"></i>
            </button>
        </div>
    </div>
</div>

{{-- Modal --}}
<div class="modal fade" id="imageModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen">
        <div class="modal-content bg-dark">
            <div class="modal-header border-0">
                <button type="button" class="btn-close btn-close-white"
                        data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body d-flex align-items-center justify-content-center">
                <img id="previewImage" class="preview-image" alt="معاينة الصورة">
            </div>
        </div>
    </div>
</div>
<style> .slider-wrapper { width: 100%; display: flex; flex-direction: column; } .slider-container { display: flex; gap: 12px; align-items: flex-start; } .slider-thumbnails-vertical { display: flex; flex-direction: column; gap: 8px; padding: 0; background: transparent; overflow-y: auto; max-height: 400px; flex-shrink: 0; } .slider-thumbnails-vertical::-webkit-scrollbar { width: 6px; } .slider-thumbnails-vertical::-webkit-scrollbar-track { background: #e9ecef; border-radius: 10px; } .slider-thumbnails-vertical::-webkit-scrollbar-thumb { background: #e30613; border-radius: 10px; } .slider-thumbnails-vertical::-webkit-scrollbar-thumb:hover { background: #c20510; } .slider-component { position: relative; overflow: hidden; flex: 1; height: 400px; background: #f8f9fa; } .carousel-inner { height: 100%; } .carousel-item { height: 100%; display: flex; align-items: center; justify-content: center; } .carousel-img { width: 100%; height: 100%; object-fit: contain; cursor: pointer; transition: transform 0.3s ease; } .carousel-img:hover { transform: scale(1.02); } .no-image-container { width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; background: linear-gradient(135deg, #f5f6fa 0%, #e9ecef 100%); } .carousel-img.no-image-placeholder { background: linear-gradient(135deg, #f5f6fa 0%, #e9ecef 100%); object-fit: contain; padding: 20px; } .carousel-control-prev, .carousel-control-next { width: 45px; height: 45px; background: rgba(227, 6, 19, 0.8); border-radius: 50%; top: 50%; transform: translateY(-50%); opacity: 0; transition: all 0.3s ease; } .carousel-control-prev:hover, .carousel-control-next:hover { background: rgba(227, 6, 19, 1); opacity: 1; } .slider-component:hover .carousel-control-prev, .slider-component:hover .carousel-control-next { opacity: 1; } .carousel-control-prev { left: 15px; } .carousel-control-next { right: 15px; } .slider-zoom-btn { position: absolute; bottom: 15px; right: 15px; width: 45px; height: 45px; background: rgba(227, 6, 19, 0.9); border: none; border-radius: 50%; color: white; font-size: 18px; cursor: pointer; transition: all 0.3s ease; z-index: 10; display: flex; align-items: center; justify-content: center; } .slider-zoom-btn:hover { background: rgba(227, 6, 19, 1); transform: scale(1.1); } .thumbnail-item { flex-shrink: 0; width: 70px; height: 70px; border: 2px solid transparent; border-radius: 6px; overflow: hidden; cursor: pointer; transition: all 0.3s ease; background: white; } .thumbnail-item img { width: 100%; height: 100%; object-fit: cover; } .thumbnail-item:hover { border-color: #e30613; transform: scale(1.05); } .thumbnail-item.active { border-color: #e30613; box-shadow: 0 0 0 2px rgba(227, 6, 19, 0.2); } .preview-image { max-width: 90vw; max-height: 90vh; object-fit: contain; } @media (max-width: 992px) { .slider-container { gap: 8px; } .slider-thumbnails-vertical { max-height: 350px; } .slider-component { height: 350px; } .thumbnail-item { width: 65px; height: 65px; } } @media (max-width: 768px) { .slider-container { flex-direction: column; gap: 12px; } .slider-thumbnails-vertical { flex-direction: row; max-height: none; overflow-x: auto; overflow-y: hidden; max-width: 100%; } .slider-component { height: 300px; } .carousel-control-prev, .carousel-control-next { width: 40px; height: 40px; } .slider-zoom-btn { width: 40px; height: 40px; font-size: 16px; } .thumbnail-item { width: 60px; height: 60px; } } @media (max-width: 480px) { .slider-component { height: 250px; } .thumbnail-item { width: 55px; height: 55px; } } </style>
@push('js')
    <script>
        function openImagePreview(src) {
            document.getElementById('previewImage').src = src;
        }

        document.addEventListener('DOMContentLoaded', function () {

            document.querySelectorAll('.slider-container').forEach(container => {

                const carouselEl = container.querySelector('.carousel');
                const thumbnails = container.querySelectorAll('.thumbnail-item');

                if (!carouselEl || !thumbnails.length) return;

                const carousel = bootstrap.Carousel.getOrCreateInstance(carouselEl);

                thumbnails.forEach((thumb, index) => {
                    thumb.addEventListener('click', () => {
                        carousel.to(index);
                    });
                });

                carouselEl.addEventListener('slid.bs.carousel', function (e) {
                    thumbnails.forEach((thumb, i) => {
                        thumb.classList.toggle('active', i === e.to);
                    });
                });
            });

        });
        console.log(typeof bootstrap);
    </script>
@endpush

