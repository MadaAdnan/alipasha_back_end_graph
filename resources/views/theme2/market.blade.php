@extends('theme2.layouts.master')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-3">

            <x-components.side-bar-category-market-component :categories="$categories" :store="$store"/>
        </div>
        <div class="col-md-9">
            <x-components.product-container-component :products="$products->items()"
                                                     />

            <x-components.paginator-component :paginator="$products"/>
        </div>
    </div>
</div>

@endsection
