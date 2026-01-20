@props([
    'carts'=>null
])
<div class="sidebar-categories-container">
    <div class="categories-header">
        <i class="fas fa-list"></i>
        <span>التجار</span>
    </div>
    <ul class="categories-list">
        @if($carts!=null)
            @foreach($carts as $cart)

                <li class="category-item-wrapper">

                    <x-components.seller-cart-item-component :$seller="$cart->seller"/>

                </li>
            @endforeach
        @endif
    </ul>
</div>
