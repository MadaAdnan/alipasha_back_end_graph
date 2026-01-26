@extends('theme2.layouts.master')
@section('content')
    <div class="container mb-2 mt-5">
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

            <div class="col-md-3  my-2 sticky-col">
                <x-components.side-bar-category-component :categories="$categories"/>
                <x-components.filter-component/>
            </div>

            <div class="col-md-9 scroll-col">
                <div class="d-flex w-100 align-items-center gap-2">
                   <div class="flex-grow-1">
                       <x-components.bread-crumb-component class="bg-transparent pt-4 "/>
                   </div>
                    <x-component.top-add-post-component />

                </div>

            @if($categoriesWithProducts)
                @foreach($categoriesWithProducts as $category)
                    <x-components.product-container-component :products="$category->products" :category="$category" count="{{$categories->find($category->id)?->products_count}}"/>
                @endforeach

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
            .sticky-col {
                display: none;
            }

            .col-md-9 {
                width: 100%;
            }
        }
    </style>

@endsection
