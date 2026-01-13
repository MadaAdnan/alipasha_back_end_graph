@extends('theme2.layouts.master')
@section('content')
    <div class="container my-2">
        <div class="row">
            <div class="col-md-12">
                <x-components.bread-crumb-component/>
            </div>
            <div class="col-md-3  my-2 sticky-col">
                <x-components.side-bar-category-component :categories="$categories"/>
                <x-components.filter-component/>
            </div>

            <div class="col-md-9 scroll-col">

            @if($categoriesWithProducts)
                @foreach($categoriesWithProducts as $category)
                    <x-components.product-container-component :products="$category->products" :category="$category" count="{{$categories->find($category->id)?->products_count}}"/>
                @endforeach

                @endif
            </div>
        </div>
    </div>

@endsection
