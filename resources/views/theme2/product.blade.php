@extends('theme2.layouts.master')

@section('content')
    <div class="container mb-2 mt-5">
        <div class="row justify-content-center">
            <div class="col-md-9">
                <x-component.slider-component :items="$post->getImages('images')"/>

            </div>
            <div class="col-md-3">
                <x-component.seller-info-component :seller="$post->user"/>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-9">
                <h2 class="fw-bold">{{$post->name??$post->expert}}</h2>
               <div class="d-flex  align-items-center px-1 py-1">
                   <x-components.bread-crumb-component class="flex-grow-1 pt-3 px-2" :first="$post->category?->name" urlFirst=" "
                                                       iconFirst=" " :categories="[
    ['name'=>$post->sub1?->name],
    ['name'=>$post->sub2?->name],
    ['name'=>$post->sub3?->name],
    ['name'=>$post->sub4?->name],
]"/>
                   <x-components.price-component class="bg-transparent " :price="$post->price" :discount="$post->discount" :isDiscount="$post->is_discount"/>
               </div>
                @if($post->video!=null && Str::startsWith($post->video ,"https://"))
                <div class="row">
                    <div class="col-md-2">
                        <x-components.play-video-component :product="$post"/>
                    </div>
                </div>
                @endif
                <div class="bg-white p-3">
                   <div class="d-flex justify-content-between">
                       <h4 class="text-black fw-bold">التفاصيل</h4>
                       <div class="">
                           <x-components.like-btn-component class="mx-2" :post="$post"/>
                        <x-components.share-btn-component :url="route('posts.show', $post->id)"/>

                       </div>
                   </div>
                    <p class="lead text-justify">{!! $post->info !!}</p>
                </div>
            </div>
            <div class="col-md-3"></div>
        </div>
    </div>

@endsection
