@props([ 'id' => 'main-slider', 'items' => [], ])
<div class="slider-container d-flex flex-column flex-md-row flex-sm-col-reverse">
    <!-- Thumbnails -->
    @if(count($items) > 1)
        <div class="d-flex flex-row flex-md-column justify-content-center mt-2 thumbnails-wrapper">
            @foreach($items as $index => $item)
                <div class="thumbnail-item mx-1 @if($loop->first) active @endif"
                     data-carousel-id="carousel-{{ $id }}"
                     data-index="{{ $index }}"
                     onclick="goToSlide(this)"
                     style="cursor:pointer; border:2px solid transparent;">
                    <img src="{{ $item }}"
                         class="img-fluid"
                         style="width:60px; height:60px; object-fit:cover;"
                         onerror="this.src='{{ asset('images/noImage.jpeg') }}'">
                </div>
            @endforeach
        </div>
    @endif
    <!-- Main Carousel -->
    <div id="carousel-{{ $id }}" class="carousel slide flex-grow-1" data-bs-ride="carousel">
        <div class="carousel-inner">
            @foreach($items as $index => $item)
                <div class="carousel-item @if($loop->first) active @endif">
                    <img src="{{ $item }}"
                         class="d-block w-100 main-image"
                         alt="صورة {{ $index + 1 }}"
                         onerror="this.src='{{ asset('images/noImage.jpeg') }}'">
                </div>
            @endforeach
        </div>

        @if(count($items) > 1)
            <button class="carousel-control-prev" type="button" data-bs-target="#carousel-{{ $id }}" data-bs-slide="prev">
                <span class="carousel-control-prev-icon"></span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carousel-{{ $id }}" data-bs-slide="next">
                <span class="carousel-control-next-icon"></span>
            </button>
        @endif
    </div>


</div>

@push('js')
    <script>
        function goToSlide(thumbnail) {
            const carouselId = thumbnail.dataset.carouselId;
            const index = parseInt(thumbnail.dataset.index);

            const carouselElement = document.getElementById(carouselId);
            const carousel = bootstrap.Carousel.getOrCreateInstance(carouselElement);

            carousel.to(index);

            // تحديث active للصور المصغّرة
            const thumbnailsWrapper = thumbnail.closest('.slider-container').querySelector('.thumbnails-wrapper');
            thumbnailsWrapper.querySelectorAll('.thumbnail-item').forEach((item, i) => {
                item.classList.toggle('active', i === index);
                item.style.border = i === index ? '2px solid #ffc107' : '2px solid transparent';
            });
        }

        // تحديث الصور المصغّرة عند السحب أو الأسهم
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.carousel').forEach(carousel => {
                carousel.addEventListener('slid.bs.carousel', function (e) {
                    const sliderContainer = carousel.closest('.slider-container');
                    const thumbnailsWrapper = sliderContainer.querySelector('.thumbnails-wrapper');
                    if (!thumbnailsWrapper) return;

                    const thumbnails = thumbnailsWrapper.querySelectorAll('.thumbnail-item');
                    thumbnails.forEach((thumb, index) => {
                        thumb.classList.toggle('active', index === e.to);
                        thumb.style.border = index === e.to ? '2px solid #ffc107' : '2px solid transparent';
                    });
                });
            });
        });
    </script>
@endpush
