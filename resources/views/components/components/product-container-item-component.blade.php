@props([
    'product'=>null,
])
@if($product)
    <div class="card shadow-sm rounded-4 overflow-hidden my-1">

        <!-- Image -->
        <a href="{{route('posts.show',$product->id)}}">
            <div class="position-relative">
                <img
                    src="{{$product->getImage()}}"
                    class="card-img-top"
                    alt="property"
                    style="height: 220px; object-fit: cover;"
                >
                @if($product->level=='special')
                    <!-- Badge -->
                    <span class="badge bg-gold position-absolute top-0 end-0 m-3 px-3 py-2">
            ممول
        </span>
                @endif
            </div>
        </a>

        <!-- Body -->
        <div class="card-body">

            <x-components.seller-name-component :seller="$product->user"/>
            <!-- Title -->
            <a href="{{route('posts.show',$product->id)}}">
                <h6 class="card-title fw-bold text-dark my-1 card-address w-100 overflow-hidden">
                    {{$product->name ?? $product->expert}}
                </h6>
            </a>

            <!-- Price -->
            <div class="fw-bold text-success fs-5 ">
                <x-components.price-component :price="$product->price" :discount="$product->discount"
                                              :isDiscount="$product->is_discount"/>
            </div>

            <!-- Info -->
            <div class="d-flex flex-wrap gap-3 text-muted small">

                <div class="d-flex align-items-center gap-1">
                    <i class="fa fa-calendar"></i> <span>{{$product->created_at?->format('Y-m')}}</span>
                </div>

                <div class="d-flex align-items-center gap-1">
                    <i class="fa fa-comments"></i> <span>{{$product->comments_count}}</span>
                </div>

                <div class="d-flex align-items-center gap-1">
                    <i class="fa fa-eye"></i> <span>{{$product->views_count}}</span>
                </div>

            </div>

            <!-- Location -->
            <div class="d-flex align-items-center gap-1 text-muted small mt-2 card-address">
                <i class="fa fa-map-pin"></i> <span>{{$product->user?->address}}</span>
            </div>

        </div>
    </div>
@else
    <div></div>
@endif
