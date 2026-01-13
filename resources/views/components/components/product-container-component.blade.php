@props([
    'products'=>null,
    'category'=>null,
    'count'=>0
])
@php
    $dataProducts=$products??[];
    $categoryName=$category?->name;
    $categoryId=$category?->id;
@endphp

<div class="card card-body my-2 ">
    @if($categoryName!=null)
        <div class="d-flex justify-content-between">
            <h3 class="fs-4 text-gray">{{$categoryName}} @if($count>0) <span class="fs-6 text-muted">({{$count}})</span> @endif</h3>
            @if($categoryId!=null)
                <a href="{{route('category.show',['id'=>$categoryId])}}" class="fs-6 text-green">مشاهدة المزيد</a>
            @endif
        </div>

    @endif
    @if($products)
        <div class="row justify-content-center">
            @foreach($dataProducts as $product)
                <div class="col-6 col-sm-6 col-md-6 col-lg-4 col-xl-3 p-1">
                <x-components.product-container-item-component :product="$product"/>
                </div>
            @endforeach

        </div>
    @endif
</div>

