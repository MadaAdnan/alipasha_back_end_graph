@props([
    'product'=>null,
    'class'=>null
])
@if($product)
    <div class="product-card-modern {{$class}}">

        <!-- Image Container -->
        <a href="{{route('posts.show',$product->id)}}" class="product-image-link">
            <div class="product-image-wrapper">
                <img
                    src="{{$product->getImage()}}"
                    class="product-image-modern"
                    alt="{{$product->name ?? $product->expert}}"
                    style="object-fit: cover;"
                    onerror="this.src='{{ asset('images/noImage.jpeg') }}'; this.classList.add('product-image-placeholder');"
                >

                <!-- Badge -->
                @if($product->level=='special')
                    <span class="product-badge-modern product-badge-featured">
                        <i class="fas fa-star"></i> ممول
                    </span>
                @elseif($product->is_discount)
                    <span class="product-badge-modern product-badge-discount">
                        <i class="fas fa-tag"></i> عرض
                    </span>
                @endif

                <!-- Overlay -->
                <div class="product-overlay-modern">
                    <button class="product-view-btn-modern">
                        <i class="fas fa-eye"></i> عرض التفاصيل
                    </button>
                </div>
            </div>
        </a>

        <!-- Body -->
        <div class="product-body-modern">

            <!-- Seller Info -->
            <div class="product-seller-info-modern">
                <x-components.seller-name-component :seller="$product->user" class="fs-6" iconSize="fs-7"/>
            </div>

            <!-- Title -->
            <a href="{{route('posts.show',$product->id)}}" class="product-title-link-modern">
                <h6 class="product-title-modern">
                    {{$product->name ?? $product->expert}}
                </h6>
            </a>

            <!-- Price -->
            <div class="product-price-modern">
                <x-components.price-component :price="$product->price" :discount="$product->discount"
                                              :isDiscount="$product->is_discount"/>
            </div>

            <!-- Stats -->
            <div class="product-stats-modern">
                <div class="stat-item-modern">
                    <i class="fas fa-heart"></i>
                    <span>{{\App\Helpers\GlobalHelper::formatNumber($product->likes_count)}}</span>
                </div>

                <div class="stat-item-modern">
                    <i class="fas fa-eye"></i>
                    <span>{{\App\Helpers\GlobalHelper::formatNumber($product->views_count)}}</span>
                </div>
            </div>

            <!-- Location -->
            <div class="product-location-modern">
                <i class="fas fa-map-marker-alt"></i>
                <span>{{$product->user?->address}}</span>
            </div>

        </div>
    </div>
@else
    <div></div>
@endif
