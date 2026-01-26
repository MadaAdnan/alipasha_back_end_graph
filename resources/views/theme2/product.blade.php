@extends('theme2.layouts.master')

@section('content')
    <div class="product-page-wrapper">
        <div class="container-fluid product-container">
            <!-- Main Product Section -->
            <div class="product-main-section">
                <!-- Product Images and Seller Info Row -->
                <div class="product-header-row">
                    <div class="product-images-col">
                        <x-component.slider-component :items="$post->getImages('images')"/>
                    </div>
                    <div class="product-seller-col">
                        <x-component.seller-info-component :seller="$post->user" :productId="$post->id" :post="$post"/>
                        @if($post->user->plans()->whereNot('duration','free')->exists())
                            <x-components.social-seller-component :store="$post->user"/>
                        @endif
                    </div>
                </div>

                <!-- Product Title and Price Section -->
                <div class="product-title-section">
                    <div class="product-title-wrapper">
                        <h1 class="product-title">{{$post->name??$post->expert}}</h1>
                        <div class="product-breadcrumb-price">
                            <x-components.bread-crumb-component class="product-breadcrumb" :first="$post->category?->name"
                                                                urlFirst=" "
                                                                iconFirst=" " :categories="[
                ['name'=>$post->sub1?->name],
                ['name'=>$post->sub2?->name],
                ['name'=>$post->sub3?->name],
                ['name'=>$post->sub4?->name],
            ]"/>
                            <x-components.price-component class="product-price-display" :price="$post->price"
                                                          :discount="$post->discount" :isDiscount="$post->is_discount"/>
                        </div>
                    </div>
                </div>

                <!-- Product Video Section -->
                @if($post->video!=null && Str::startsWith($post->video ,"https://"))
                    <div class="product-video-section">
                        <x-components.play-video-component :product="$post"/>
                    </div>
                @endif

                <!-- Product Details Section -->
                <div class="product-details-section">
                    <div class="categories-header">
                        <i class="fa-solid fa-file-lines"></i>
                        <span>التفاصيل</span>
                    </div>
                    <div class="product-details-content">
                        <div class="product-actions-bar">
                            @if(auth()->check())
                                <x-components.add-to-cart-component :post="$post"/>
                                <x-components.like-btn-component class="mx-2" :post="$post"/>
                                <a href="{{url('/admin/products/'.$post->id)}}" class="btn">
                                    <span>تعديل</span>
                                    <i class="fa fa-edit"></i>
                                </a>
                            @endif
                            <x-components.share-btn-component :url="route('posts.show', $post->id)"/>
                        </div>

                        <div class="product-description">
                            <p class="lead text-justify">{!! $post->info !!}</p>
                        </div>

                        <!-- Product Info Items -->
                        <div class="product-info-items">
                            @if($post->colors->count()>0)
                                <div class="info-item">
                                    <span class="info-label"><i class="fas fa-palette"></i> الألوان المتوفرة</span>
                                    <div class="info-value colors-container">
                                        @foreach($post->colors as $color)
                                            <span class="color-dot" style="background-color: {{$color->code}}" title="{{$color->name ?? 'لون'}}"></span>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                            <div class="info-item">
                                <span class="info-label"><i class="fa-solid fa-file-lines"></i> معرف المنشور</span>
                                <span class="info-value">#{{$post->id}}</span>
                            </div>
                            <div class="info-item">
                                <span class="info-label"><i class="fas fa-map-marker-alt"></i> المحافظة</span>
                                <span class="info-value">{{$post->user?->city?->name}}</span>
                            </div>
                            <div class="info-item">
                                <span class="info-label"><i class="fas fa-map-pin"></i> العنوان</span>
                                <span class="info-value">{{$post->user?->address}}</span>
                            </div>
                            <div class="info-item">
                                <span class="info-label"><i class="fas fa-phone"></i> الهاتف</span>
                                <span class="info-value">{{$post->user?->full_phone}}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Comments Section -->
                <x-components.comments-component :post="$post" :comments="$comments"/>

                <!-- Related Products Section -->
                <x-components.same-post-component :category="$post->sub1_id"/>
            </div>
        </div>
    </div>

    <style>
        .product-page-wrapper {
            padding: 20px 0;
            background: #f8f9fa;
            min-height: calc(100vh - 100px);
        }

        .product-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 15px;
        }

        .product-main-section {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }

        /* Header Row - Images and Seller */
        .product-header-row {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 30px;
            padding: 30px;
            border-bottom: 1px solid #f0f0f0;
        }

        .product-images-col {
            display: flex;
            align-items: center;
        }

        .product-seller-col {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        /* Title Section */
        .product-title-section {
            padding: 30px;
            border-bottom: 1px solid #f0f0f0;
        }

        .product-title-wrapper {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .product-title {
            font-size: 28px;
            font-weight: 700;
            color: var(--gray-text);
            margin: 0;
            line-height: 1.4;
        }

        .product-breadcrumb-price {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 20px;
            flex-wrap: wrap;
        }

        .product-breadcrumb {
            flex: 1;
            min-width: 200px;
        }

        .product-price-display {
            background: transparent !important;
        }

        /* Stats Section */
        .product-stats-section {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 15px;
            padding: 20px 30px;
            border-bottom: 1px solid #f0f0f0;
        }

        .product-stat-item {
            display: flex;
        }

        .stat-widget {
            border: 1px solid #e0e0e0 !important;
            border-radius: 8px !important;
            padding: 15px !important;
            background: #f8f9fa !important;
            width: 100%;
        }

        /* Details Section */
        .product-details-section {
            border-bottom: 1px solid #f0f0f0;
        }

        .product-details-content {
            padding: 30px;
            display: flex;
            flex-direction: column;
            gap: 25px;
        }

        .product-actions-bar {
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
            align-items: center;
        }

        .product-description {
            line-height: 1.8;
            color: var(--gray-text);
        }

        .product-description p {
            margin: 0;
        }

        /* Info Items */
        .product-info-items {
            display: flex;
            flex-direction: column;
            gap: 0;
        }

        .info-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 0;
            border-bottom: 1px solid #f0f0f0;
        }

        .info-item:last-child {
            border-bottom: none;
        }

        .info-label {
            font-weight: 600;
            color: var(--gray-text);
            display: flex;
            align-items: center;
            gap: 10px;
            min-width: 150px;
        }

        .info-label i {
            color: var(--red);
            font-size: 16px;
        }

        .info-value {
            color: var(--gray-text);
            text-align: right;
        }

        .colors-container {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            justify-content: flex-end;
        }

        .color-dot {
            width: 35px;
            height: 35px;
            border-radius: 50%;
            border: 2px solid #e0e0e0;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .color-dot:hover {
            border-color: var(--red);
            transform: scale(1.1);
            box-shadow: 0 4px 8px rgba(227, 6, 19, 0.2);
        }

        /* Responsive Design */
        @media (max-width: 992px) {
            .product-header-row {
                grid-template-columns: 1fr;
                gap: 20px;
                padding: 20px;
            }

            .product-title {
                font-size: 24px;
            }

            .product-breadcrumb-price {
                flex-direction: column;
                align-items: flex-start;
            }

            .product-stats-section {
                grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
                padding: 15px 20px;
            }

            .product-details-content {
                padding: 20px;
                gap: 20px;
            }

            .info-item {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }

            .info-value {
                text-align: left;
            }

            .colors-container {
                justify-content: flex-start;
            }
        }

        @media (max-width: 768px) {
            .product-page-wrapper {
                padding: 10px 0;
            }

            .product-container {
                padding: 0 10px;
            }

            .product-header-row {
                padding: 15px;
                gap: 15px;
            }

            .product-title-section {
                padding: 15px;
            }

            .product-title {
                font-size: 20px;
            }

            .product-stats-section {
                grid-template-columns: 1fr;
                padding: 10px 15px;
                gap: 10px;
            }

            .product-details-content {
                padding: 15px;
                gap: 15px;
            }

            .product-actions-bar {
                gap: 10px;
            }

            .info-label {
                min-width: auto;
                font-size: 14px;
            }

            .color-dot {
                width: 30px;
                height: 30px;
            }
        }
    </style>

@endsection
