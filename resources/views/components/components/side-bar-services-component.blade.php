@props([
     'services'=>[],
     'serviceId'=>null
])


<div class="sidebar-categories-container">
    <div class="categories-header">
        <i class="fas fa-list"></i>
        <span>التصنيفات</span>
    </div>
    <ul class="categories-list">
        @if($services)
        @foreach($services as $service)
            <li class="category-item-wrapper">
                <x-components.side-bar-services-item-component :service="$service"/>
            </li>
        @endforeach
        @endif
    </ul>
</div>

