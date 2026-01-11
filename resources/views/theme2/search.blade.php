@extends('theme2.layouts.master')
@section('content')
    <div class="container my-2">
        <div class="row">
            <div class="col-md-12">
                <x-components.bread-crumb-component :categories="[
    ['id'=>null,'name'=>'الفلتر']
]"/>
            </div>
            <div class="col-md-3  my-2">
                <x-components.side-bar-category-component :categories="$categories"/>
                <x-components.filter-component/>
            </div>

            <div class="col-md-9">
                @if($products)
                    @foreach($products as $product)
                        <x-components.estate-card-component :product="$product"/>
                    @endforeach
                @else
<div class="col-9"><div class="bg-white p-2 rounded">لا يوجد اى نتائج</div>
                @endif
            </div>
        </div>
    </div>

@endsection
