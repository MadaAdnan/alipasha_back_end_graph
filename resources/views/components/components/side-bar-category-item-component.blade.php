@props([
    'category'=>null,
    'categoryId'=>null
])
@php
    $dataCategory=$category;
    $category_id=$categoryId??null;
    $isActive = $category_id != null && $dataCategory?->id == $category_id;
@endphp

<a class="category-item-link {{ $isActive ? 'active' : '' }}" href="{{route('category.show',$dataCategory?->id)}}">
    <div class="category-item-icon">
        <i class="fas fa-tag"></i>
        <span>{{ $dataCategory?->name }}</span>
    </div>
    <div class="category-item-title">
        <i class="fas fa-chevron-left"></i>
    </div>
</a>

@if($isActive && $dataCategory->children->count() > 0)
    <ul class="subcategories-list">
        @foreach($dataCategory->children as $child)
            <li class="subcategory-item">
                <a class="subcategory-item-link" href="{{route('category.show',$child?->id)}}">
                    <div class="category-item-icon">
                        <i class="fas fa-circle" style="font-size: 6px;"></i>
                        <span>{{ $child?->name }}</span>
                    </div>
                    <div class="category-item-title">
                        <i class="fas fa-chevron-left"></i>
                    </div>
                </a>
            </li>
        @endforeach
    </ul>
@endif

