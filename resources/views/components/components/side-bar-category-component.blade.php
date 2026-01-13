@props([
     'categories'=>null,
     'categoryId'=>0
])
@php
    $dataCategories=$categories??[];
@endphp

    <div class="bg-white p-1 rounded">
        <dl>
        @foreach($dataCategories as $category)

            <x-components.side-bar-category-item-component :categoryId="$category->id"    :category="$category" />
        @endforeach
        </dl>
    </div>

