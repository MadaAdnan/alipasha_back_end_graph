@extends('theme2.layouts.master')

@section('content')
    <div class="container mb-2 mt-5">
        <div class="row justify-content-center">
            <div class="col-md-9">
                <x-component.slider-component :items="$post->getImages('images')"/>

            </div>
            <div class="col-md-3">
                <x-component.seller-info-component :seller="$post->user" :productId="$post->id"/>
                @if($post->user->plans()->whereNot('duration','free')->exists())
                    <x-components.social-seller-component :store="$post->user"/>
                @endif
            </div>
        </div>
        <div class="row">
            <div class="col-md-9">
                <h2 class="fw-bold">{{$post->name??$post->expert}}</h2>
                <div class="d-flex  align-items-center px-1 py-1">
                    <x-components.bread-crumb-component class="flex-grow-1 pt-3 px-2" :first="$post->category?->name"
                                                        urlFirst=" "
                                                        iconFirst=" " :categories="[
    ['name'=>$post->sub1?->name],
    ['name'=>$post->sub2?->name],
    ['name'=>$post->sub3?->name],
    ['name'=>$post->sub4?->name],
]"/>
                    <x-components.price-component class="bg-transparent " :price="$post->price"
                                                  :discount="$post->discount" :isDiscount="$post->is_discount"/>
                </div>
                <div class="row justify-content-center align-items-center my-1">
                    @if($post->video!=null && Str::startsWith($post->video ,"https://"))

                        <div class="col-md-2">
                            <x-components.play-video-component :product="$post"/>
                        </div>

                    @endif
                    <div class="col-md-2 col-6">
                        <x-components.small-widget-product-detail-component class="border rounded p-2 bg-white"
                                                                            title="تاريخ النشر"
                                                                            icon="fas fa-calendar d-block fs-4 text-gray"
                                                                            info="{{$post->created_at?->format('Y-m-d')}}"/>
                    </div>
                    <div class="col-md-2 col-6">
                        <x-components.small-widget-product-detail-component class="border rounded p-2 bg-white"
                                                                            title="عدد المشاهدات"
                                                                            icon="fa fa-eye d-block fs-4 text-gray"
                                                                            info="{{$post->views_count}}"/>
                    </div>
                </div>
                <div class="categories-header">
                    <i class="fa-solid fa-file-lines"></i>
                    <span>التفاصيل</span>
                </div>
                <div class="bg-white p-3">

                    <div class="d-flex justify-content-between">
                        <h4 class="text-black fw-bold"></h4>
                        <div class="">
                            @if(auth()->check())
                                <x-components.add-to-cart-component :post="$post"/>
                                <x-components.like-btn-component class="mx-2" :post="$post"/>
                            @endif

                            <x-components.share-btn-component :url="route('posts.show', $post->id)"/>

                        </div>
                    </div>
                    <p class="lead text-justify">{!! $post->info !!}</p>
                    @if($post->colors->count()>0)
                        <div class="d-flex justify-content-between info-data">
                            <span><i class="fas fa-palette"></i> الألوان المتوفرة</span>
                            <div class="d-flex">
                                @foreach($post->colors as $color)
                                    <span class="p-2 rounded-circle color"
                                          style="background-color: {{$color->code}}"></span>
                                @endforeach
                            </div>
                        </div>
                    @endif
                    <div class="d-flex justify-content-between info-data">
                        <span><i class="fa-solid fa-file-lines"></i> معرف المنشور</span>
                        <span> #{{$post->id}}</span>
                    </div>
                    <div class="d-flex justify-content-between info-data">
                        <span><i class="fas fa-map-marker-alt"></i> المحافظة</span>
                        <span>{{$post->user?->city?->name}}</span>
                    </div>
                    <div class="d-flex justify-content-between info-data">
                        <span><i class="fas fa-map-pin"></i> العنوان</span>
                        <span>{{$post->user?->address}}</span>
                    </div>
                    <div class="d-flex justify-content-between info-data">
                        <span><i class="fas fa-phone"></i> الهاتف</span>
                        <span>{{$post->user?->full_phone}}</span>
                    </div>
                </div>
                <x-components.comments-component :post="$post" :comments="$comments"/>
            </div>

            <div class="col-md-3">
                <x-components.social-seller-component :store="$post->user"/>
            </div>

        </div>
        <div class="row">
            <div class="col-md-9">

                <x-components.same-post-component :category="$post->sub1_id"/>
            </div>
        </div>
    </div>

@endsection
