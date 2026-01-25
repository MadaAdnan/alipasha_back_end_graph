<div class="card card-body my-2 ">

        <div class="d-flex justify-content-between">
            <h3 class="fs-4 text-gray">منتجات ذات صلة</h3>

        </div>

    @if($products)
        <div class="row g-2">
            @foreach($products as $product)
                <div class="col-lg-3 col-md-4 col-sm-6 col-6">
                    <x-components.product-container-item-component :product="$product"/>
                </div>
            @endforeach

        </div>
    @endif
</div>
