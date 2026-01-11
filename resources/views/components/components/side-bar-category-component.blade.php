@props([
     'categories'=>null
])
@php
    $dataCategories=$categories??[];
@endphp
<div class="col-md-3">
    <div class="bg-white">
        @foreach($dataCategories as $category)
            <x-components.side-bar-category-item-component :category="$category" />
        @endforeach
    </div>
</div>
