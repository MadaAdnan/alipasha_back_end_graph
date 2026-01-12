@props([
     'categories'=>null,
     'categoryId'=>null
])
@php
    $dataCategories=$categories??[];
@endphp

    <div class="bg-white p-1 rounded">
        @foreach($dataCategories as $category)
            <x-components.side-bar-category-item-component @if($category$categoryId != null && $category->id==$categoryId) :categoryId="$category->id"  @endif  :category="$category" />
        @endforeach
    </div>

