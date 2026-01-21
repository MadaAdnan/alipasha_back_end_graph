<div class="card card-body my-2 ">
    @if($category!=null)
        <div class="d-flex justify-content-between">
            <h3 class="fs-4 text-gray">{{$categoryName}} @if($count>0) <span class="fs-6 text-muted">({{$count}})</span> @endif</h3>
            @if($showMore)
                <a href="{{route('category.show',['id'=>$categoryId])}}" class="fs-6 text-green">مشاهدة المزيد</a>
            @endif
        </div>
    @else
        <div class="d-flex justify-content-between">
            <h3 class="fs-4 text-gray">{{$categoryName}} @if($count>0) <span class="fs-6 text-muted">({{$count}})</span> @endif</h3>

        </div>
    @endif
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
