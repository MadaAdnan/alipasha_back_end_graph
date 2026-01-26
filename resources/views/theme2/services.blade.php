@extends('theme2.layouts.master')
@section('content')
    <div class="container mb-2 mt-5">
        <div class="row justify-content-center">
            <div class="col-md-3"></div>
        <div class="col-md-9">
            <div class="row justify-content-center">
                <div class="col-md-4 col-6">
                    <x-components.small-widget-product-detail-component classTitle="fs-4"
                                                                        classInfo="fs-4"
                                                                        class="bg-white rounded my-1 py-2" title="المنشورات" icon="fa-solid fa-file-signature fs-4" info="{{\App\Helpers\GlobalHelper::formatNumber($services_count)}}"/>
                </div>
                <div class="col-md-4 col-6">
                    <x-components.small-widget-product-detail-component title="المشاهدات" icon="fa fa-eye fs-4" info="{{\App\Helpers\GlobalHelper::formatNumber($views)}}" classTitle="fs-4"
                                                                        classInfo="fs-4"
                                                                        class="bg-white rounded my-1 py-2"/>
                </div>
                <div class="col-md-4 col-6">
                    <x-components.small-widget-product-detail-component title="المزودين" icon="fa fa-users fs-4" info="{{\App\Helpers\GlobalHelper::formatNumber($sellers)}}" classTitle="fs-4"
                                                                        classInfo="fs-4"
                                                                        class="bg-white rounded my-1 py-2"/>
                </div>
            </div>
        </div>
        </div>

        <!-- Mobile Categories Scroll -->
        <div class="mobile-categories-scroll">
            <a href="{{ route('services.index') }}" class="category-btn @if(!request()->get('category_id')) active @endif">
                الكل
            </a>
            @foreach($categories as $category)
                <a href="{{ route('services.index', ['category_id' => $category->id]) }}"
                   class="category-btn @if(request()->get('category_id') == $category->id) active @endif">
                    {{ $category->name }}
                </a>
            @endforeach
        </div>

        <div class="row my-4">

            <div class="col-md-3  my-2 sticky-col">
                <x-components.side-bar-services-component :services="$categories" :serviceId="$category->id"/>
                <x-components.filter-component :showPrice="false" :showTextSearch="true" route="{{route('services.index')}}" type="{{\App\Enums\CategoryTypeEnum::SERVICE->value}}"/>
            </div>
            <div class="col-md-9 mt-3">
                @if($services->count()>0)
                <x-components.services-component :services="$services->items()"/>
                <x-components.paginator-component :paginator="$services"/>
                @else
                    <div class="border border-1 rounded w-100 px-2 py-1 mt-1 bg-white fs-4 text-red-accent">
                        لا يوجد خدمات
                    </div>
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
