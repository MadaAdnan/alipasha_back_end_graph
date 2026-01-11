@props([
    'product'=>null,
    'class'=>null
])
<div class="estate-card d-flex border rounded overflow-hidden {{$class}}">

    {{-- السلايدر --}}
    <div id="estateCarousel{{ $product->id }}" class="carousel slide estate-image" data-bs-ride="carousel">

        {{-- الصور --}}
        <div class="carousel-inner">
            @foreach($product->getMedia('images') as $index => $img)
                <div class="carousel-item {{ $index == 0 ? 'active' : '' }}">
                    <img src="{{ $img->getUrl('webp') }}" class="d-block slider-img" alt="image">
                </div>
            @endforeach
        </div>

        {{-- الأسهم --}}
        <button class="carousel-control-prev" type="button" data-bs-target="#estateCarousel{{ $product->id }}" data-bs-slide="prev">
            <span class="carousel-control-prev-icon"></span>
        </button>

        <button class="carousel-control-next" type="button" data-bs-target="#estateCarousel{{ $product->id }}" data-bs-slide="next">
            <span class="carousel-control-next-icon"></span>
        </button>

        {{-- النقاط --}}
        <div class="carousel-indicators">
            @foreach($product->getMedia('images') as $index => $img)
                <button type="button"
                        data-bs-target="#estateCarousel{{ $product->id }}"
                        data-bs-slide-to="{{ $index }}"
                        class="{{ $index == 0 ? 'active' : '' }}">
                </button>
            @endforeach
        </div>
    </div>

    {{-- التفاصيل --}}
    <div class="estate-info p-2 flex-fill">

        <h6 class="estate-title pe-2 ">{{ $product->expert }}</h6>

        <div class="estate-price text-success fw-bold ">
            <x-components.price-component  :price="$product->price" :discount="$product->discount" :isDiscount="$product->is_discount" />

        </div>

        <div class="estate-meta d-flex align-items-center gap-3 text-secondary small mb-3">
            <span><i class="fa fa-ruler"></i> {{ $product->city?->name }} م²</span>
            <span><i class="fa fa-seedling"></i> {{ $product->type }}</span>
            <span><i class="fa fa-calendar"></i> {{ $product->created_at?->format('Y-m') }}</span>
            <span><i class="fa fa-map-marker-alt"></i> {{ $product->user?->address }}</span>
        </div>

        <div class="estate-actions d-flex gap-4 align-items-center">
            <a href="{{ $product->user?->full_phone }}" class="text-danger text-decoration-none fw-bold">
                <i class="fa fa-phone"></i> الاتصال
            </a>

            <a href="{{ $product->whatsapp_url }}" class="text-success fw-bold text-decoration-none">
                <i class="fa fa-whatsapp"></i> WhatsApp
            </a>
        </div>

    </div>

</div>
