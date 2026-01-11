@props([
    'products'=>null,
    'category'=>null,
])
@php
    $dataProducts=$products??[];
    $categoryName=$category?->name;
    $categoryId=$category?->id;
@endphp

<div class="card card-body my-2">
    @if($categoryName!=null)
        <div class="d-flex justify-content-between">
            <h3 class="fs-6 text-red">{{$categoryName}}</h3>
            @if($categoryId!=null)
                <a href="{{route('category.show',['id'=>$categoryId])}}" class="fs-6">مشاهدة المزيد</a>
            @endif
        </div>
    @endif
    @if($products)
        <div class="row justify-content-center">
            @foreach($dataProducts as $product)
                <div class="col-md-4">
                <x-components.product-container-item-component :product="$product"/>
                </div>
            @endforeach

        </div>
    @endif
</div>

