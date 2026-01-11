@extends('theme2.layouts.master')
@section('content')
    <div class="container my-2">
        <div class="row">
            <div class="col-md-12">
                <x-components.bread-crumb-component :categories="[]"/>
            </div>
            <x-components.side-bar-category-component :categories="$categories"/>
            <div class="col-md-9">

                @if($categoriesWithProducts)
                    @foreach($categoriesWithProducts as $category)
                        <x-components.product-container-component :products="$category->products" :category="$category"/>
                    @endforeach

                @endif
            </div>
        </div>
    </div>

@endsection

