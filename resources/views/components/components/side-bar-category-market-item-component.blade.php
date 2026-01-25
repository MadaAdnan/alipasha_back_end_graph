@props([
    'category'=>null,
    'categoryId'=>null,
    'seller'=>null,
])
@php
    $dataCategory=$category;
    $category_id=$categoryId??null;
    $isActive = $category_id != null && $dataCategory?->id == $category_id;
@endphp

<a class="category-item-link {{ $isActive ? 'active' : '' }}" href="{{route('seller.profile',['id'=>$seller?->id,'category_id'=>$category?->id])}}">
    <div class="category-item-icon">
        <i class="fas fa-tag"></i>
        <span>{{ $dataCategory?->name }}</span>
    </div>
    <div class="category-item-title">
        <i class="fas fa-chevron-left"></i>
    </div>
</a>



