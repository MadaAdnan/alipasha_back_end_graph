@props([
    'products'=>null,
    'category'=>null,
])
@php
    $dataProducts=$products??[];
    $categoryName=$category?->name;
    $categoryId=$category?->id;
@endphp
<div class="col-md-9">
    <div class="card card-body">
        @if($categoryName!=null)
            <div class="d-flex justify-content-between">
                <h3 class="fs-6">{{$categoryName}}</h3>
                @if($categoryId!=null)
                    <a href="{{route('category.show',['id'=>$categoryId])}}" class="fs-6">مشاهدة المزيد</a>
                @endif
            </div>
        @endif
       @if($products)
                <div class="row justify-content-center">
                    @foreach($dataProducts as $product)
                        <x-components.product-container-item-component :product="$product"/>
                    @endforeach

                </div>
           @endif
    </div>
</div>
