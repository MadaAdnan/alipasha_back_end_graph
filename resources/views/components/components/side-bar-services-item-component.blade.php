@props([
    'service'=>null,
    'serviceId'=>null
])
@php


    $isActive = $serviceId != null && $service?->id == $serviceId;

@endphp

<a class="category-item-link {{ $isActive ? 'active' : '' }}" href="{{route('services.index',['category_id'=>$service?->id])}}">
    <div class="category-item-icon">
        <i class="fas fa-tag"></i>
        <span>{{ $service?->name }}</span>
    </div>
    <div class="category-item-title">
        <i class="fas fa-chevron-left"></i>
    </div>
</a>



