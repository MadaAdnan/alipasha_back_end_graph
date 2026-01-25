@props([
     'categories'=>null,
     'categoryId'=>null
])
@php
    $dataCategories=$categories??[];
@endphp

<div class="sidebar-categories-container">
    <div class="categories-header">
        <i class="fas fa-list"></i>
        <span>التصنيفات</span>
    </div>
    <ul class="categories-list">
        @foreach($dataCategories as $category)
            <li class="category-item-wrapper">
                <x-components.side-bar-category-item-component :category="$category" :categoryId="$categoryId" />
            </li>
        @endforeach
    </ul>
</div>

