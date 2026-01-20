@props([
    'carts'=>[]
])
<div class="sidebar-categories-container">
    <div class="categories-header">
        <i class="fas fa-list"></i>
        <span>التجار</span>
    </div>
    <ul class="categories-list">
        @foreach($carts as $cart)

            <li class="category-item-wrapper">
                <x-components.seller-cart-item-component :$seller="$cart->seller"/>

            </li>
        @endforeach
    </ul>
</div>
