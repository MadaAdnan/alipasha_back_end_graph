@props([
     'services'=>null,
     'serviceId'=>null
])


<div class="sidebar-categories-container">
    <div class="categories-header">
        <i class="fas fa-list"></i>
        <span>التصنيفات</span>
    </div>
    <ul class="categories-list">
        @foreach($services as $service)
            <li class="category-item-wrapper">
                <x-components.side-bar-services-item-component :service="$service"/>
                <x-components.side-bar-category-item-component :category="$category" :categoryId="$categoryId" />
            </li>
        @endforeach
    </ul>
</div>

