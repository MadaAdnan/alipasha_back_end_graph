@extends('theme2.layouts.master')
@section('content')
    <div class="container my-2">
        <div class="row">
            <div class="col-md-12">
                <x-components.bread-crumb-component :categories="[]"/>
            </div>
            <x-components.side-bar-category-component :categories="$categories" :categoryId="$category->id"/>
            <div class="col-md-9">
                        <x-components.product-container-component :products="$category->products" :category="$category"/>

                <x-components.paginator-component :paginator="$cloneProducts"/>
            </div>
        </div>
    </div>

@endsection

