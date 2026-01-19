@props([
    'community'=>null,
    'communityId'=>null
])
@php
dd($community);
    $isActive = $communityId != null && $community?->id == $communityId;
@endphp

<a class="category-item-link {{ $isActive ? 'active' : '' }}" href="{{route('category.show',$community?->id)}}">
    <div class="category-item-icon">
        <i class="fas fa-tag"></i>
        <span>{{ $community?->name }}</span>
    </div>
    <div class="category-item-title">
        <i class="fas fa-chevron-left"></i>
    </div>
</a>



