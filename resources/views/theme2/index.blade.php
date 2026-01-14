@extends('theme2.layouts.master')
@section('content')
    <div class="container mb-2 mt-5">
        <div class="row">

            <div class="col-md-3  my-2 sticky-col">
                <x-components.side-bar-category-component :categories="$categories"/>
                <x-components.filter-component/>
            </div>

            <div class="col-md-9 scroll-col">
                <div class="d-flex w-100 align-items-center gap-2">
                   <div class="flex-grow-1">
                       <x-components.bread-crumb-component class="bg-transparent pt-4 "/>
                   </div>
                    <x-component.top-add-post-component />

                </div>

            @if($categoriesWithProducts)
                @foreach($categoriesWithProducts as $category)
                    <x-components.product-container-component :products="$category->products" :category="$category" count="{{$categories->find($category->id)?->products_count}}"/>
                @endforeach

                @endif
            </div>
        </div>
    </div>

@endsection
