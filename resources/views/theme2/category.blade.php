@extends('theme2.layouts.master')
@section('content')
    <div class="container mb-2 mt-5">
        <div class="row">

               <div class="col-md-3  my-2 sticky-col">
                <x-components.side-bar-category-component :categories="$category->children" :categoryId="$category->id"/>
                <x-components.filter-component/>
            </div>

            <div class="col-md-9">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        @php
                            $breadCrumbs = [];
                            if($category->parents->count()>0){
                                $parent=$category->parents->first();
                                $breadCrumbs[]= [
                                    'name' => $parent?->name,
                                    'url' => route('category.show', $parent->id)
                                ];
                            }
                            $breadCrumbs[]= [
                                    'name' => $category->name,

                                ];
                        @endphp
                        <x-components.bread-crumb-component :categories="$breadCrumbs" class="bg-transparent"/>
                    </div>
                    <x-component.top-add-post-component/>
                </div>

                <x-components.product-container-component :products="$products->items()" :category="$category"
                                                          :showMore="false"/>

                <x-components.paginator-component :paginator="$cloneProducts"/>
            </div>
        </div>
    </div>

@endsection

