@extends('theme2.layouts.master')

@section('content')
<div class="container my-5">
    <div class="row">
        <div class="col-md-3">

            <x-components.side-bar-category-market-component :categories="$categories" :store="$store" />
        </div>
        <div class="col-md-9">
            <x-components.product-container-component :products="$products->items()"
                                                     :categoryName="($categories->where('id',request()->get('category_id'))->first()?->name)?? 'جميع المنتجات'"/>

            <x-components.paginator-component :paginator="$products"/>
        </div>
    </div>
</div>

@endsection
