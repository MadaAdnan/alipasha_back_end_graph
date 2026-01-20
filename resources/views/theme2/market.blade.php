@extends('theme2.layouts.master')

@section('content')
    <div class="container my-5">
        <div class="row justify-content-between align-items-center">
            <div class="col-md-9">
                <div class="row justify-content-center">
                    <div class="col-md-2">
                        <x-components.small-widget-product-detail-component class="bg-white border rounded p-2"
                                                                            title="متابعين"
                                                                            icon="fa-regular fa-thumbs-up"
                                                                            :info="$store->followers_count"/>

                    </div>
                    <div class="col-md-2">
                        <x-components.small-widget-product-detail-component class="bg-white border rounded p-2"
                                                                            title="المنتجات"
                                                                            icon="fa-solid fa-boxes-stacked"
                                                                            :info="$store->products_count"/>

                    </div>

                </div>
            </div>
            <div class="col-md-3">
                <x-component.seller-info-component :seller="$store"/>
            </div>
        </div>

        <div class="row">
            <div class="col-md-3  my-2 sticky-col">

                <x-components.side-bar-category-market-component :categories="$categories" :store="$store"/>
                @if($store->plans()->whereNot('duration','free')->exists())
                    <x-components.social-seller-component :store="$store"/>
                @endif
            </div>
            <div class="col-md-9">
                <x-components.product-container-component :products="$products->items()"
                                                          :categoryName="($categories->where('id',request()->get('category_id'))->first()?->name)?? 'جميع المنتجات'"/>

                <x-components.paginator-component :paginator="$products"/>
            </div>
        </div>
    </div>

@endsection
