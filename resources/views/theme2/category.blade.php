@extends('theme2.layouts.master')
@section('content')
    <div class="container my-2">
        <div class="row">
            <div class="col-md-12">
                <x-components.bread-crumb-component :categories="[]"/>
            </div>
            <div class="col-md-3">
                <x-components.side-bar-category-component :categories="$categories" :categoryId="$category->id"/>
            </div>

            <div class="col-md-9">
                <x-component.top-add-post-component />
                        <x-components.product-container-component :products="$products" :category="$category" :showMore="false"/>

            <x-components.paginator-component :paginator="$cloneProducts"/>
            </div>
        </div>
    </div>

@endsection

