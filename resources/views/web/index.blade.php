@extends('layouts.master_layouts')
@section('content')

    <div class="container-fluid" style="margin-top: 70px">
        <div class="row">
        @if(auth()->check())
            <!-- Right Section (2 columns on large screens, 0 on small) -->
                <div id="right-sidebar" class="col-4 d-none d-xl-block">
                    <div class="media-scroll bg-light p-4 ">
                        <div class="inbox">
                            <p class="title">صندوق الوارد</p>
                            <ul>
                                {{--<li><a href="#">إظهار الكل</a></li>--}}
                                <li><a href="{{route('invoices.index')}}" class="p-1 border border-1 border-danger rounded ">مبيعاتي</a></li>
                                <li><a href="{{route('my-invoices.index')}}" class="p-1 border border-1 border-danger rounded ">مشترياتي</a></li>
                                <li><a href="{{route('orders.index')}}" class="p-1 border border-1 border-danger rounded">شحن علي باشا</a></li>
                                <li><a href="{{route('index')}}" class="p-1 border border-1 border-danger rounded ">الإشعارات</a></li>
                            </ul>
                        </div>

                        @forelse($notifications as $notification)
                            <div class="notification-item">
                                <div class="info">
                                    <div>
                                        {{--  <a href="../pages/profile.html">
                                              <img src="{{asset('assets/avatar-2.svg')}}" alt="avatar"/>
                                          </a>--}}
                                        <p class="title">{{$notification->data['title']}}</p>
                                        <p class="title">{{$notification->data['body']}}</p>
                                    </div>
                                    <p class="time">{{$notification->created_at->diffForHumans()}}</p>
                                </div>
                                <div class="actions">

                                    {{-- <form action="" method="POST" style="width: 100%;">
                                         <input type="hidden" name="storId" value="123"/>
                                         <button type="submit" class="btn btn-danger action-buttons"

                                                 style="color: #fff; background-color: #e30613"
                                         > قبول الطلب
                                         </button>
                                     </form>--}}

                                    @if(isset($notification->data['url']) && $notification->data['url']!='')
                                        @php
                                            $uri=\League\Uri\Uri::new($notification->data['url']);
    $route=$notification->data['url'];

    if($uri->getPath()=='/product'){
        $id=Str::replace('id=','',$uri->getQuery());
        if($id!=null){
            $route=route('posts.show',['id'=>$id]);
        }

    }
                                        @endphp
                                        <a class="btn btn-danger action-buttons"
                                           data-path="{{$uri->getPath()}}"
                                           href="{{$route}}"
                                           style="color: #fff; background-color: #e30613"
                                        > إذهب
                                        </a>
                                    @endif


                                    {{-- <form action="" method="POST" style="width: 100%;">
                                         <input type="hidden" name="storId" value="123"/>
                                         <button type="submit" class="btn btn-danger"
                                                 class="action-buttons"
                                                 style="color: #000000; background-color: #e4e6eb"
                                         > عرض الطلبية
                                         </button>
                                     </form>--}}

                                </div>
                            </div>
                        @empty
                            <p>لا يوجد إشعارات</p>
                        @endforelse


                        <div class="chat-wrapper">
                            <div class="chats">
                                <p class="title">المحادثات</p>
                                <ul>
                                    <li>
                                        <i
                                            class="bi bi-search"
                                            style="margin-right: 8px; color: #aaa"
                                        ></i>
                                    </li>
                                </ul>
                            </div>
                            @if($communities!=null)
                                @foreach($communities as $community)
                                    <div class="chat-item">
                                        <div
                                            style="
                  display: flex;
                  align-items: center;
                  gap: 4px;
                  margin-bottom: 8px;
                "
                                        >
                                            <a href="{{route('communities.show',$community->id)}}"
                                               class="rounded-circle">
                                                <img class="rounded-circle"  src="@if($community->hasMedia('image') && $community->type!=\App\Enums\CommunityTypeEnum::CHAT->value) {{$community->getImage()}} @else {{$community->users()->where('users.id','!=',auth()->id())->first()?->getImage()}}  @endif "
                                                     style="width: 45px;height: 45px" alt="avatar"/>
                                            </a>
                                            @php
                                                if($community->type==\App\Enums\CommunityTypeEnum::CHAT->value){
        $user=$community->users()->whereNot('users.id',auth()->id())->first();
        $name=$user->seller_name ?? $user->name;
    }else{
        $name=$community->name;
    }
                                            @endphp
                                            <a href="{{route('communities.show',$community->id)}}">
                                                <p class="title d-flex flex-column">
                                                    <span>{{$name}}</span>
                                                    <span
                                                        class="text-muted small">عدد المشتركين : {{$community->users_count}}</span>
                                                </p>
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                                <a class="btn btn-sm btn-outline-info w-100" href="{{route('communities.index')}}">
                                    جميع محادثاتي
                                </a>

                            @endif
                        </div>
                    </div>
                </div>
            @else
                <div id="right-sidebar" class="col-4 d-none d-xl-block"></div>
        @endif


        <!-- Middle Section (12 columns on small, 8 on larger screens) -->
            <div class="col-12 col-xl-5">


                <div class="container mt-4 bg-white p-2 rounded-4">
                    <div class="stories-container">
                        <div class="story-box">
                            <div class="add-market">
                                <img src="{{asset('assets/add-market.svg')}}" alt="Story 1"/>
                                <p>أضف متجرك هنا</p>
                            </div>
                        </div>
                        {{--                        Special Seller--}}
                        @foreach($specialSeller as $seller)
                            <div class="story-box">
                                <a href="{{route('seller.profile',$seller->id)}}">
                                    <img src="{{$seller->getFirstMediaUrl('custom','webp')}}" alt="Story 2"/>
                                </a>
                                <div class="info">
                                    <p>{{$seller->seller_name}}</p>
                                    <a href="{{route('seller.profile',$seller->id)}}">
                                        <img src="{{$seller->getFirstMediaUrl('image','webp')}}" alt="avatar"/>
                                    </a>
                                </div>
                            </div>
                        @endforeach


                    </div>
                </div>
                @auth
                    <div class="new-post">
                        <div class="flex-wrapper">
                            <input
                                class="post-input form-control border-0 shadow-none"
                                type="search"
                                placeholder="..ماذا تفكر أن تنشر"
                                aria-label="Search"
                                data-bs-toggle="modal"
                                data-bs-target="#addPostModal"
                            />
                            <a href="{{route('profile.index')}}">
                                <img src="{{asset('assets/avatar.svg')}}" alt="" class="avatar"/>
                            </a>
                        </div>
                        <div class="divider"></div>

                        <div
                            class="post-actions"
                            style="
                margin: 10px 0px;
                display: flex;
                align-items: center;
                justify-content: space-around;
              "
                        >
                            <div data-bs-toggle="modal"
                                 data-bs-target="#addServiceModal"
                                 style="display: flex; align-items: center; gap: 4px; cursor: pointer;">
                                <img src="{{asset('assets/post-action-services.svg')}}" alt=""/>
                                <p class="sub-title">خدمة</p>
                            </div>
                            <div data-bs-toggle="modal"
                                 data-bs-target="#addPorsaModal"
                                 style="display: flex; align-items: center; gap: 4px; cursor: pointer;">
                                <img src="{{asset('assets/post-action-chart.svg')}}" alt=""/>
                                <p class="sub-title">مناقصة</p>
                            </div>
                            <div data-bs-toggle="modal"
                                 data-bs-target="#addJobModal"
                                 style="display: flex; align-items: center; gap: 4px; cursor: pointer;">
                                <img src="{{asset('assets/post-action-jobs.svg')}}" alt=""/>
                                <p class="sub-title">وظيفة</p>
                            </div>
                            <div data-bs-toggle="modal"
                                 data-bs-target="#addPostModal"
                                 style="display: flex; align-items: center; gap: 4px; cursor: pointer;">
                                <img src="{{asset('assets/post-action-products.svg')}}" alt=""/>
                                <p class="sub-title">منتج</p>
                            </div>
                        </div>
                    </div>
                @endauth

                @foreach($products as $product)
                    <div class="posts">
                        <div class="post">
                            <div class="post-header">
                                <button
                                    class="btn btn-light"
                                    data-bs-toggle="popover"
                                    data-bs-placement="bottom"
                                    data-bs-content-id="popover-content"
                                    tabindex="0"
                                    role="button"
                                >
                                    <i class="bi bi-three-dots"></i>
                                </button>

                                <div id="popover-content" class="d-none">
                                    <i class="bi bi-trash me-2"></i>Delete
                                </div>

                                <div class="post-info">
                                    <div style="display: flex; gap: 4px; flex-direction: column">
                                        <p class="title" style="text-align: right">
                                            @if($product->user?->is_verified==true)
                                                <i class="bi bi-patch-check" style="color: blue; font-size: 16px;"></i>
                                            @endif
                                                <a href="{{route('seller.profile',$product->user_id)}}" class="text-black">
                                            {{$product->user?->seller_name}}
                                                </a></p>

                                        @if($product->type!=\App\Enums\CategoryTypeEnum::NEWS->value)
                                            <p class="d-block sub-title" style="text-align: right">
                                                {{$product->city?->name}} - {{$product->category?->name}}
                                                - {{$product->sub1?->name}}
                                            </p>
                                        @endif
                                    </div>
                                    <a href="{{route('seller.profile',$product->user_id)}}">
                                        <img width="46" height="46" class="rounded-circle"
                                             src="{{$product->user?->getImage()}}" alt=""/>
                                    </a>
                                </div>
                            </div>

                            <div class="post-content " style="margin: 20px 0px 0px 0px">


                                <a
                                    @if($product->type==\App\Enums\CategoryTypeEnum::SEARCH_JOB->value || $product->type==\App\Enums\CategoryTypeEnum::JOB->value )

                                    href="{{route('jobs.show',$product->id)}}"
                                    @elseif($product->type==\App\Enums\CategoryTypeEnum::TENDER->value )
                                    href="{{route('tenders.show',$product->id)}}"
                                    @elseif($product->type==\App\Enums\CategoryTypeEnum::RESTAURANT->value ||  $product->type==\App\Enums\CategoryTypeEnum::PRODUCT->value )
                                    href="{{route('posts.show',$product->id)}}"
                                    @endif>
                                    <p class="title" style="text-align: right">
                                        {{$product->expert}}
                                    </p>
                                </a>
                                <div
                                    style="
                    width: 100%;
                    height: 100%;
                    border-radius: 10px;
                    overflow: hidden;
                  "
                                >
                                    <a @if($product->type==\App\Enums\CategoryTypeEnum::SEARCH_JOB->value || $product->type==\App\Enums\CategoryTypeEnum::JOB->value )

                                       href="{{route('jobs.show',$product->id)}}"
                                       @elseif($product->type==\App\Enums\CategoryTypeEnum::TENDER->value )
                                       href="{{route('tenders.show',$product->id)}}"
                                       @elseif($product->type==\App\Enums\CategoryTypeEnum::RESTAURANT->value ||  $product->type==\App\Enums\CategoryTypeEnum::PRODUCT->value )
                                       href="{{route('posts.show',$product->id)}}"
                                        @endif>
                                        <img
                                            style="
                      width: 100%;
                      object-fit: cover;
                      margin: 5px 0px 0px 0px;
                    "
                                            src="@if($product->hasMedia('image')) {{$product->getImage('image')}} @else {{$product->getImage('images')}} @endif"
                                            alt="post-img"
                                        />
                                    </a>
                                </div>
                            </div>

                            <div
                                style="margin: 20px 0px 0px 0px; padding: 0px 10px; display: flex; align-items: center; justify-content: space-between;">
                                <div class="price"
                                     style="width: 90px; height: 24px; padding: 5px; border-radius: 4px; color: #fff; background-color: #aaa; display: flex; align-items: center; justify-content: center; border: 5px; font-size: 12px;">
                                    @if($product->is_delivery)
                                        متوفر شحن
                                    @else
                                        غير متوفر شحن
                                    @endif
                                </div>
                                @if($product->type==\App\Enums\CategoryTypeEnum::PRODUCT->value)
                                    <div style="display: flex; gap: 8px;">

                                        <div class="price"
                                             style=" height: 24px; padding: 5px; border-radius: 4px; color: #fff; background-color: #e60613; display: flex; align-items: center; justify-content: center; border: 5px;">
                                            @if($product->is_discount)
                                                <del class="text-secondary">{{$product->price}}</del>
                                                {{$product->discount}}

                                            @else
                                                {{$product->price}}
                                            @endif
                                            $
                                        </div>

                                        @if($product->is_delivery)
                                            <form action="{{route('carts.store')}}" method="POST" style="
                      width: 60px;
                      height: 24px;
                      background-color: #e30613;
                      color: #fff;
                      border-radius: 4px;
                      font-size: 12px;
                      display: flex;
                      align-items: center;
                      justify-content: center;
                    ">
                                                @csrf
                                                @method('post')
                                                <input type="hidden" name="productId" value="{{$product->id}}"/>
                                                <button type="submit"
                                                        style="background-color: transparent; border: none; display: flex; align-items: center; gap: 8px; color: #fff;">
                                                    <i class="bi bi-cart-fill"></i>
                                                </button>
                                            </form>
                                        @endif

                                    </div>
                                @endif

                            </div>

                            <div class="post-actions"
                                 style="margin: 20px 0px 0px 0px; padding: 0px 10px; display: flex; align-items: center; justify-content: space-between;">
                                <button
                                    class="copy-link"
                                    data-post-link="{{route('posts.show',$product->id)}}"
                                    style="display: flex; align-items: center; gap: 8px; background-color: transparent;"
                                >
                                    <i style="font-size: 12px;" class="bi bi-share"></i>
                                    <p class="sub-title">مشاركة</p>
                                </button>
                                <button
                                    style="display: flex; align-items: center; gap: 8px; background-color: transparent;">
                                    <i style="font-size: 12px;" class="bi bi-eye"></i>
                                    <p class="sub-title">مشاهدات {{$product->views_count}}</p>
                                </button>
                                @if($product->type==\App\Enums\CategoryTypeEnum::PRODUCT->value)
                                    <form action="{{route('post.like')}}" method="POST"
                                          style="display: flex; align-items: center; gap: 8px; background-color: transparent;">
                                        @csrf
                                        @method('POST')
                                        <input type="hidden" name="productId" value="{{$product->id}}"/>
                                        <button type="button"

                                                @if(auth()->check())  onclick="Like('{{route('api.like',['productId'=>$product->id,'userId'=>auth()->id()])}}','Like-{{$product->id}}')" @endif
                                                style="background-color: transparent; border: none; display: flex; align-items: center; gap: 8px;">
                                            <i style="font-size: 12px;" class="bi bi-hand-thumbs-up"></i>
                                            <p class="sub-title" >  <span id="Like-{{$product->id}}">{{$product->likes_count}}</span> اعجاب</p>
                                        </button>

                                    </form>
                                @endif
                                <a href="{{route('posts.show',$product->id)}}">
                                    <button
                                        style="display: flex; align-items: center; gap: 8px; background-color: transparent;">
                                        <i style="font-size: 12px;" class="bi bi-chat-dots"></i>
                                        <p class="sub-title">تعليق</p>
                                    </button>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach

                <div class="d-flex justify-content-between">
                    <a class="btn btn-sm btn-secondary"
                       href="{{$products->withQueryString()->nextPageUrl()}}">التالي</a>
                    <a class="btn btn-sm btn-secondary" href="{{$products->withQueryString()->previousPageUrl()}}">السابق</a>
                </div>


            </div>
            <div
                class="floating-left-sidebar-icon d-xl-none"
                onclick="toggleLeftSidebar()"
            >
                التصنيفات
            </div>
            <!-- Left Section (2 columns on large screens, 0 on small) -->
            <div id="left-sidebar" class="col-3 d-none d-xl-block">
                <div class="media-scroll bg-light p-4">
                    <div style="text-align: center">
                        <a href="{{url('/seller/products/create')}}">
                            <button
                                class="new-post"
                                {{--   data-bs-toggle="modal"
                                   data-bs-target="#addPostModal"--}}
                            >
                                منشور جديد
                            </button>
                        </a>
                    </div>
                    <div class="categories">
                        <p class="category-text">التصنيفات</p>
                        <div class="divider"></div>
                        @foreach($categories as $category)
                            <a href="{{route('category.show',$category->id)}}">
                                <div class="category-item">

                                    <p>{{$category->name}}</p>
                                    <div class="count">{{$category->products_count}}</div>

                                </div>
                            </a>
                        @endforeach


                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection



