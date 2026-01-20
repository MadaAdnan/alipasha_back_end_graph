@props([
    'seller'=>null
])
<div class="card card-body my-2 ">


        <div class="row g-2">
            @if($items?->count()>0)
            @foreach($items as $product)
                <div class="col-lg-3 col-md-4 col-sm-6 col-6">
                    <x-components.cart-item-component :product="$product"/>
                </div>
            @endforeach
            @endif
            <div class="col-12">
                لا يوجد عناصر في السلة
            </div>
        </div>

</div>
