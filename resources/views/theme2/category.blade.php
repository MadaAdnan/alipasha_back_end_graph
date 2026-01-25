@extends('theme2.layouts.master')
@section('content')
    <div class="container mb-2 mt-5">
        <!-- Mobile Categories Scroll -->
        <div class="mobile-categories-scroll">
            <a href="{{ route('category.show', $category->id) }}" class="category-btn active">
                {{ $category->name }}
            </a>
            @foreach($category->children as $child)
                <a href="{{ route('category.show', $child->id) }}"
                   class="category-btn">
                    {{ $child->name }}
                </a>
            @endforeach
        </div>

        <div class="row">

               <div class="col-md-3  my-2 sticky-col">
                <x-components.side-bar-category-component :categories="[$category,...$category->children]" :categoryId="$category->id"/>
                <x-components.filter-component :categoryId="$category->id"/>
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

    <style>
        /* Mobile Categories Scroll */
        .mobile-categories-scroll {
            display: none;
            overflow-x: auto;
            overflow-y: hidden;
            padding: 8px 0;
            margin-bottom: 12px;
            gap: 8px;
            flex-wrap: nowrap;
            scrollbar-width: none;
            -ms-overflow-style: none;
        }

        .mobile-categories-scroll::-webkit-scrollbar {
            display: none;
        }

        .category-btn {
            display: inline-flex;
            padding: 8px 16px;
            background: #f0f0f0;
            color: #212529;
            border-radius: 20px;
            text-decoration: none;
            font-size: 13px;
            font-weight: 500;
            white-space: nowrap;
            transition: all 0.3s ease;
            border: 1px solid transparent;
        }

        .category-btn:hover {
            background: #e9ecef;
        }

        .category-btn.active {
            background: #e30613;
            color: white;
            border-color: #e30613;
        }

        @media (max-width: 768px) {
            /* Show categories scroll on mobile */
            .mobile-categories-scroll {
                display: flex !important;
            }

            /* Hide sidebar on mobile */
            .sticky-col {
                display: none;
            }

            .col-md-9 {
                width: 100%;
            }
        }
    </style>

@endsection

