@props([
     'categories'=>null
])
@php
    $dataCategories=$categories??[];
@endphp
<div class="col-md-3  my-2">
    <div class="bg-white p-1 rounded">
        @foreach($dataCategories as $category)
            <x-components.side-bar-category-item-component :category="$category" />
        @endforeach
    </div>
</div>
