@extends('layouts.master_layouts')
@section('title')
    {{$service->name ?? Str::words($service->expert,2)}}
@endsection
@section('content')
    <div class="container-fluid" style="margin-top: 70px">
        <div class="row">



            <div class="col-12 col-xl-9" style="margin-top: 10px">
                <div class="containter" style="background-color: #fff; padding: 16px; border-radius: 16px;">
                    <h1  class="title mb-4" style="text-align: right;"> خدمة : {{$service->category?->name}} </h1>
                    <div class="card mb-3" style="width: 100%;">
                        <div class="row g-0">
                            <div class="col-md-8">
                                <div class="card-body" dir="rtl">
                                    <h5 class="card-title">{{$service->name}}</h5>

                                    <p class="card-text">{!! $service->info !!}</p>
                                    @if($service->url)
                                        <a href="{{$service->url}}" class="btn btn-sm"></a>
                                        @endif
                                    <p class="card-text">{{$service->category?->name}}</p>
                                    <p class="card-text" > <i class="bi bi-telephone" style="font-size: 14px; color: red;"></i> {{$service->phone}}</p>
                                    <p class="card-text"> <i class="bi bi-geo-alt" style="font-size: 14px; color: red;"></i>  {{$service->city?->name}}    </p>
                                    <p class="card-text"> <i class="bi bi-geo-alt" style="font-size: 14px; color: red;"></i> {{$service->address}} </p>
                                    <p class="card-text"><small class="text-muted">{{$service->created_at?->diffForHumans()}}</small></p>
                                    @php
                                        $sellerId=\App\Models\Setting::first()->support_id;
                                    @endphp
                                    <form action="{{route('communities.store')}}" method="post">
                                        @csrf
                                        @method('POST')

                                        <input type="hidden" name="sellerId" value="{{$sellerId}}">
                                        <button type="submit" class="btn" style="width: 100%; background-color: #e30613; color: #fff; margin: 20px 0px;">إبلاغ عن الخدمة</button>
                                    </form>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <img src="{{$service->getImage()}}" class="w-100 img-fluid rounded-start" alt="...">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div
                class="floating-left-sidebar-icon d-lg-none"
                onclick="toggleLeftSidebar()"
            >
                التصنيفات
            </div>
            <!-- Left Section (2 columns on large screens, 0 on small) -->
            <div id="left-sidebar" class="col-3 d-none d-xl-block">
                <div class="media-scroll bg-light p-4 h-100">
                    <div style="text-align: center">
                        <button class="new-post"  data-bs-toggle="modal"
                                data-bs-target="#addServiceModal">أضف خدمة غير متوفرة</button>
                    </div>
                    <div class="categories">
                        <p class="category-text">التصنيفات</p>
                        <div class="divider"></div>
                        @foreach($categories as $category)
                        <div class="category-item">
                            <p><a href="{{route('services.index',['category'=>$category->id])}}">{{$category->name}}</a></p>
                            <div class="count">{{$category->products2_count}}</div>
                        </div>
                        @endforeach

                    </div>
                </div>
            </div>


        </div>
    </div>

@endsection
