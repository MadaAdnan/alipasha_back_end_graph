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
    <div class="categories-scroll-wrapper">
        <ul class="categories-list">
            @foreach($dataCategories as $category)
                <li class="category-item-wrapper">
                    <x-components.side-bar-category-item-component :category="$category" :categoryId="$categoryId" />
                </li>
            @endforeach
        </ul>
    </div>
</div>

<style>
    .sidebar-categories-container {
        background: white;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        overflow: hidden;
        margin-bottom: 20px;
    }

    .categories-header {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 16px;
        background: linear-gradient(135deg, #e30613 0%, #ff5f57 100%);
        color: white;
        font-weight: 600;
        font-size: 16px;
    }

    .categories-header i {
        font-size: 18px;
    }

    .categories-scroll-wrapper {
        max-height: 500px;
        overflow-y: auto;
        overflow-x: hidden;
    }

    .categories-scroll-wrapper::-webkit-scrollbar {
        width: 8px;
    }

    .categories-scroll-wrapper::-webkit-scrollbar-track {
        background: #f1f1f1;
    }

    .categories-scroll-wrapper::-webkit-scrollbar-thumb {
        background: #e30613;
        border-radius: 4px;
    }

    .categories-scroll-wrapper::-webkit-scrollbar-thumb:hover {
        background: #c20510;
    }

    .categories-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .category-item-wrapper {
        border-bottom: 1px solid #e9ecef;
    }

    .category-item-wrapper:last-child {
        border-bottom: none;
    }

    @media (max-width: 768px) {
        .categories-scroll-wrapper {
            max-height: 400px;
        }

        .categories-header {
            padding: 12px;
            font-size: 14px;
        }
    }
</style>

