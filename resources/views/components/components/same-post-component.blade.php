<div class="mt-2">
    <div class="categories-header">
        <i class="fas fa-list"></i>
        <span>منتجات ذات صلة</span>
    </div>
@foreach($products as $product)
    <x-components.product-container-item-component class="my-1" :product="$product"/>
@endforeach</div>
