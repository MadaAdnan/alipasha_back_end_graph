@extends('theme2.layouts.master')

@section('content')
    <div class="store-page-wrapper">
        <!-- Store Header Section -->
        <div class="store-header-section">
        <div class="container store-header-content">
            <div class="store-header-wrapper">
                <!-- Store Image -->
                <div class="store-image-container">
                    <img src="{{ $store->getImage() }}"
                         alt="{{ $store->seller_name ?? $store->name }}"
                         class="store-image"
                         onerror="this.src='{{ asset('images/user-profile.png') }}'">
                    @if($store->is_verified)
                        <span class="store-verified-badge">
                            <i class="bi bi-patch-check-fill"></i>
                        </span>
                    @endif
                </div>

                <!-- Store Info -->
                <div class="store-info-wrapper">
                    <div class="store-name-section">
                        <h1 class="store-name">{{ $store->seller_name ?? $store->name }}</h1>
                        @if($store->is_verified)
                            <span class="verified-badge-small">
                                <i class="bi bi-patch-check-fill"></i>
                            </span>
                        @endif
                        @if(auth()->id() == $store->id)
                            <a href="{{route('profile.index')}}">
                                <i class="fa fa-edit fs-5"></i>
                            </a>
                        @endif
                    </div>

                    <p class="store-address">
                        <i class="fas fa-map-marker-alt"></i>
                        {{ $store->address }}
                    </p>

                    <!-- Store Stats -->
                    <div class="store-stats-grid">
                        <div class="stat-card">
                            <div class="stat-icon">
                                <i class="fa-solid fa-boxes-stacked"></i>
                            </div>
                            <div class="stat-content">
                                <span class="stat-value">{{ $store->products_count }}</span>
                                <span class="stat-label">منتج</span>
                            </div>
                        </div>



                        <div class="stat-card">
                            <div class="stat-icon">
                                <i class="fa fa-user-check"></i>
                            </div>
                            <div class="stat-content">
                                <span class="stat-value">{{ $store->followers_count ?? 0 }}</span>
                                <span class="stat-label">يتابع</span>
                            </div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-icon">
                                <i class="fa fa-bell"></i>
                            </div>
                            <div class="stat-content">
                                <span class="stat-value">{{ $store->following_count ?? 0 }}</span>
                                <span class="stat-label">متابع</span>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="store-actions">
                        <div class="store-actions-contact">
                            <button class="btn-action btn-contact" onclick="window.location.href='https://wa.me/{{ $store->phone_code }}{{ $store->phone }}'">
                                <i class="fa-brands fa-whatsapp"></i>
                                <span>واتساب</span>
                            </button>

                           @if(auth()->check() && auth()->id() !=$store->id)
                                <form action="{{ route('communities.store') }}" method="POST" style="display: inline;">
                                    @csrf
                                    <input type="hidden" name="sellerId" value="{{ $store->id }}">
                                    <button type="submit" class="btn-action btn-chat">
                                        <i class="fa-solid fa-comments"></i>
                                        <span>محادثة</span>
                                    </button>
                                </form>
                           @endif
                            @if(auth()->check() && auth()->id() ==$store->id)
                            <a class="btn-action btn-red-accent" href="{{route('plans.index')}}">
                                <i class="fa-solid fa-chart-line"></i>
                                <span>ترقية الحساب</span>
                            </a>
                                @endif
                        </div>

                        <!-- Social Icons -->
                        <div class="social-icons-group">
                            @if(isset($store->social['face']) && Str::start($store->social['face'],'https://'))
                            <a href="{{ $store->social['face'] }}" target="_blank" class="social-icon-btn" title="فيسبوك">
                                <i class="fa-brands fa-facebook-f"></i>
                            </a>
                            @endif
                             @if(isset($store->social['instagram'])&& Str::start($store->social['instagram'],'https://'))
                            <a href="{{ $store->social['instagram'] }}" target="_blank" class="social-icon-btn" title="إنستغرام">
                                <i class="fa-brands fa-instagram"></i>
                            </a>
                                    @endif
                                 @if(isset($store->social['tiktok'])&&Str::start($store->social['tiktok'],'https://'))
                            <a href="{{ $store->social['tiktok'] }}" target="_blank" class="social-icon-btn" title="تيك توك">
                                <i class="fa-brands fa-tiktok"></i>
                            </a>
                                    @endif
                              @if(isset($store->social['twitter'])&&Str::start($store->social['twitter'],'https://'))
                            <a href="{{ $store->social['twitter'] }}" target="_blank" class="social-icon-btn" title="تويتر">
                                <i class="fa-brands fa-twitter"></i>
                            </a>
                                    @endif
                                @if(isset($store->social['linkedin'])&&Str::start($store->social['linkedin'],'https://'))
                            <a href="{{ $store->social['linkedin'] }}" target="_blank" class="social-icon-btn" title="لينكد إن">
                                <i class="fa-brands fa-linkedin-in"></i>
                            </a>
                                @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="divider-line"></div>
        </div>
    </div>
</div>


    <div class="container products-section">
        <!-- Mobile Categories Scroll -->
        <div class="mobile-categories-scroll">
            <a href="{{ route('seller.profile', ['id' => $store->id]) }}" class="category-btn @if(!request()->get('category_id')) active @endif">
                الكل
            </a>
            @foreach($categories as $category)
                <a href="{{ route('seller.profile', ['id' => $store->id, 'category_id' => $category->id]) }}"
                   class="category-btn @if(request()->get('category_id') == $category->id) active @endif">
                    {{ $category->name }}
                </a>
            @endforeach
        </div>

        <div class="row">
            <div class="col-md-3 sticky-col">
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
    </div>

    <style>
        /* Fix for sticky positioning */
        body {
            overflow-x: hidden !important;
            overflow-y: auto !important;
        }

        .store-page-wrapper {
            background: white;
            overflow: visible !important;
        }

        .divider-line {
            height: 1px;
            background: #e9ecef;
            margin: 20px 0;
        }
        .store-header-section {
            position: relative;
            padding: 0px 0;
            margin-bottom: 0;
        }

        .products-section {
            margin-top: 0 !important;
            padding-top: 0 !important;
            overflow: visible !important;
        }

        .products-section .row {
            align-items: flex-start !important;
            overflow: visible !important;
        }

        .products-section .sticky-col {
            position: -webkit-sticky !important;
            position: sticky !important;
            top: 80px !important;
            align-self: flex-start !important;
            z-index: 10 !important;
        }

        @media (max-width: 768px) {
            .products-section {
                padding: 0 !important;
            }

            .products-section .sticky-col {
                position: relative !important;
                top: 0 !important;
            }
        }

        .store-header-content {
            position: relative;
        }

        .store-header-wrapper {
            display: flex;
            gap: 35px;
            align-items: flex-start;
            background: transparent;
            border-radius: 0;
            padding: 25px;
            box-shadow: none;
        }

        .store-image-container {
            position: relative;
            flex-shrink: 0;
        }

        .store-image {
            width: 120px;
            aspect-ratio: 1;
            border-radius: 50%;
            object-fit: cover;
        }

        .store-verified-badge {
            position: absolute;
            bottom: 0px;
            right: 0px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #0066cc;
            font-size: 28px;
        }

        .store-info-wrapper {
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .store-name-section {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .store-name {
            font-size: 32px;
            font-weight: 700;
            color: #212529;
            margin: 0;
            line-height: 1.2;
        }

        .verified-badge-small {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 24px;
            height: 24px;
            color: #0066cc;
            font-size: 18px;
        }

        .store-address {
            display: flex;
            align-items: center;
            gap: 8px;
            color: #6c757d;
            font-size: 14px;
            margin: 0;
        }

        .store-address i {
            color: #e30613;
            font-size: 16px;
        }

        .store-stats-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 12px;
            padding: 12px 0;
            border-top: 1px solid #e9ecef;
            border-bottom: 1px solid #e9ecef;
        }

        .stat-card {
            display: flex;
            flex-direction: row;
            align-items: center;
            gap: 10px;
            padding: 0;
            background: transparent;
            border-radius: 0;
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            background: transparent;
            transform: none;
        }

        .stat-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            color: #e30613;
            font-size: 24px;
            flex-shrink: 0;
        }

        .stat-content {
            display: flex;
            flex-direction: column;
            gap: 2px;
            text-align: right;
        }

        .stat-value {
            font-size: 20px;
            font-weight: 700;
            color: #212529;
            line-height: 1;
        }

        .stat-label {
            font-size: 13px;
            color: #6c757d;
            font-weight: 500;
        }

        .store-actions {
            display: flex;
            gap: 12px;
            align-items: center;
            margin-top: 0;
        }

        .store-actions-contact {
            display: flex;
            gap: 12px;
            align-items: center;
        }

        .btn-action {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 12px 24px;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            white-space: nowrap;
        }

        .btn-contact {
            background: linear-gradient(135deg, #25d366 0%, #20ba5a 100%);
            color: white;
        }

        .btn-contact:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(37, 211, 102, 0.3);
        }

        .btn-chat {
            background: linear-gradient(135deg, #e30613 0%, #c70510 100%);
            color: white;
        }

        .btn-chat:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(227, 6, 19, 0.3);
        }

        .social-icons-group {
            display: flex;
            gap: 8px;
            align-items: center;
            margin-right: auto;
        }

        .store-actions form {
            display: inline;
        }


        .social-icon-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: #f0f0f0;
            color: #212529;
            font-size: 14px;
            transition: all 0.3s ease;
            text-decoration: none;
        }

        .social-icon-btn:hover {
            background: #e30613;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(227, 6, 19, 0.3);
        }



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

        @media (max-width: 992px) {
            .store-header-wrapper {
                flex-direction: row;
                align-items: flex-start;
                text-align: right;
                gap: 20px;
                padding: 20px;
            }

            .store-image-container {
                flex-shrink: 0;
            }

            .store-image {
                width: 75px;
                aspect-ratio: 1;
            }

            .store-info-wrapper {
                flex: 1;
            }

            .store-name {
                font-size: 20px;
            }

            .store-stats-grid {
                grid-template-columns: repeat(3, 1fr);
            }

            .store-actions {
                width: 100%;
                flex-direction: row;
                flex-wrap: wrap;
            }

            .store-actions-contact {
                flex-direction: row;
            }

            .btn-action {
                width: auto;
            }

            .social-icon-btn {
                width: 24px !important;
                height: 24px !important;
                font-size: 12px !important;
                padding: 4px !important;
            }

            .social-icons-group {
                display: none !important;
            }
        }

        @media (max-width: 768px) {
            .store-header-section {
                padding: 15px 0;
                margin-bottom: 15px;
            }

            .store-header-wrapper {
                padding: 12px;
            }

            .store-image {
                width: 75px;
               aspect-ratio: 1;
            }

            .store-name {
                font-size: 20px;
            }

            .store-stats-grid {
                grid-template-columns: repeat(3, 1fr);
                gap: 12px;
                padding: 15px 0;
            }

            .stat-icon {
                font-size: 16px;
            }

            .stat-value {
                font-size: 16px;
            }

            .stat-label {
                font-size: 11px;
            }

            .btn-action {
                padding: 10px 16px;
                font-size: 12px;
            }

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
