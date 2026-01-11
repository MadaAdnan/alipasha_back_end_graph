@props([
     'categories'=>null
])
@php
    $dataCategories=$categories??[];
@endphp

    <div class="bg-white p-1 rounded">
        @foreach($dataCategories as $category)
            <x-components.side-bar-category-item-component :category="$category" />
        @endforeach
    </div>

