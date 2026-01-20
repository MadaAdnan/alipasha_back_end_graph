@props([
    'seller'=>null,
    'sellerId'=>null
])
@php

    $seller_id=$sellerId??null;
    $isActive = $seller_id != null && $seller?->id == $seller_id;
@endphp

<a class="category-item-link {{ $isActive ? 'active' : '' }}" href="{{route('carts.show',$seller?->id)}}">
    <div class="category-item-icon">
        <i class="fas fa-tag"></i>
        <span>{{ $seller?->seller_name??$seller->name }}</span>
    </div>
    <div class="category-item-title">
        <i class="fas fa-chevron-left"></i>
    </div>
</a>



