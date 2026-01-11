@props([
    'product'=>null,
])
@if($product)
<div class="card shadow-sm rounded-4 overflow-hidden my-1" >

    <!-- Image -->
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

    <!-- Body -->
    <div class="card-body">

        <x-components.seller-name-component :seller="$product->user"/>
        <!-- Title -->
        <h6 class="card-title fw-bold text-dark mb-2">
           {{$product->name ?? $product->expert}}
        </h6>

        <!-- Price -->
        <div class="fw-bold text-success fs-5 mb-3">
            {{$product->price}}
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
        <div class="d-flex align-items-center gap-1 text-muted small mt-2">
            <i class="fa fa-map-pin"></i> <span>{{$product->user?->address}}</span>
        </div>

    </div>
</div>
@else
<div></div>
@endif
