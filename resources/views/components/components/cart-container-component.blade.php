@props([
    'seller'=>null
])
<div class="card card-body my-2 ">


        <div class="row g-2">
            @if($items?->count()>0)
            @foreach($items as $product)
                <div class="col-12">
                    <x-components.cart-item-component :item="$product"/>
                </div>
            @endforeach
                <div class="col-12">
                    <div class="d-flex justify-content-between">
                        <div>
                            <a href="{{route('cart.checkout')}}" class="btn btn-primary">
                                الدفع
                            </a>
                        </div>
                        <div>
                            <a href="{{route('cart.clear')}}" class="btn btn-danger">
                                مسح
                            </a>
                        </div>
                    </div>
                </div>
            @else
                <div class="col-12">
                    لا يوجد عناصر في السلة
                </div>
            @endif

        </div>

</div>

