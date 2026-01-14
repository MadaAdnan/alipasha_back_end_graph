@extends('theme2.layouts.master')
@section('content')
    <div class="container my-2">
        <div class="row">

            <div class="col-md-3">
                <x-components.side-bar-category-component :categories="$categories" :categoryId="$category->id"/>
            </div>

            <div class="col-md-9">
                <div class="d-flex align-items-center">
                   <div class="flex-grow-1">
                       <x-components.bread-crumb-component :categories="[
    ['name'=>$category->name]
]" class="bg-transparent"/>
                   </div>
                    <x-component.top-add-post-component />
                </div>

                        <x-components.product-container-component :products="$products->items()" :category="$category" :showMore="false"/>

            <x-components.paginator-component :paginator="$cloneProducts"/>
            </div>
        </div>
    </div>

@endsection

