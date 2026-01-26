@extends('theme2.layouts.master')
@section('content')
    <div class="container my-2">
        <!-- Mobile Categories Scroll -->
        <div class="mobile-categories-scroll">
            <a href="{{ route('index') }}" class="category-btn @if(!request()->get('category_id')) active @endif">
                الكل
            </a>
            @foreach($categories as $category)
                <a href="{{ route('category.show', $category->id) }}"
                   class="category-btn">
                    {{ $category->name }}
                </a>
            @endforeach
        </div>

        <div class="row">

            <div class="col-md-3  my-2">
                <x-components.side-bar-category-component :categories="$categories"/>
                <x-components.filter-component/>
            </div>

            <div class="col-md-9 my-2">
                <x-components.bread-crumb-component :categories="[
    ['id'=>null,'name'=>'الفلتر','class'=>'active']
]" :firstUrl="route('index')" class="bg-transparent"/>
                @if($products->count()>0)
                    @foreach($products as $product)
                        <x-components.estate-card-component :product="$product" class="d-none d-md-flex mt-2"/>
                        <x-components.product-container-item-component :product="$product"
                                                                       class="d-block d-md-none mt-2"/>
                    @endforeach
                    <x-components.paginator-component :paginator="$productsForPagination"/>
                @else
                    <div class="bg-white p-2 rounded">لا يوجد اى نتائج</div>
                @endif
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
            .col-md-3 {
                display: none;
            }

            .col-md-9 {
                width: 100%;
            }
        }
    </style>

@endsection
