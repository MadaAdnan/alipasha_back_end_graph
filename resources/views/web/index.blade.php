@extends('layouts.master_layouts')
@section('content')
    <div class="container-fluid" style="margin-top: 70px">
        <div class="row">
            <!-- Right Section (2 columns on large screens, 0 on small) -->
            <div id="right-sidebar" class="col-3 d-none d-xl-block">
                <div class="media-scroll bg-light p-4 ">
                    <div class="inbox">
                        <p class="title">صندوق الوارد</p>
                        <ul>
                            <li><a href="#">إظهار الكل</a></li>
                            <li><a href="#">مبيعاتي</a></li>
                            <li><a href="#">مشترياتي</a></li>
                            <li><a href="#">الإشعارات</a></li>
                        </ul>
                    </div>

                    <div class="notification-item">
                        <div class="info">
                            <div>
                                <a href="../pages/profile.html">
                                    <img src="{{asset('assets/avatar-2.svg')}}" alt="avatar"/>
                                </a>
                                <p class="title">احمد خالد المحمد يطلب منتجات</p>
                            </div>
                            <p class="time">4H</p>
                        </div>
                        <div class="actions">

                            <form action="" method="POST" style="width: 100%;">
                                <input type="hidden" name="storId" value="123"/>
                                <button type="submit" class="btn btn-danger"
                                        class="action-buttons"
                                        style="color: #fff; background-color: #e30613"
                                > قبول الطلب
                                </button>
                            </form>


                            <form action="" method="POST" style="width: 100%;">
                                <input type="hidden" name="storId" value="123"/>
                                <button type="submit" class="btn btn-danger"
                                        class="action-buttons"
                                        style="color: #000000; background-color: #e4e6eb"
                                > عرض الطلبية
                                </button>
                            </form>

                        </div>
                    </div>


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

                        <div class="chat-item">
                            <div
                                style="
                  display: flex;
                  align-items: center;
                  gap: 4px;
                  margin-bottom: 8px;
                "
                            >
                                <a href="./pages/profile.html">
                                    <img src="{{asset('assets/avatar-2.svg')}}" alt="avatar"/>
                                </a>
                                <p class="title">عبادة كحلوس</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Middle Section (12 columns on small, 8 on larger screens) -->
            <div class="col-12 col-xl-6">

                <div id="toast"
                     style="position: fixed; bottom: 20px; right: 20px; background-color: #28a745; color: #fff; padding: 10px 20px; border-radius: 5px; display: none;">
                    Copy successfully
                </div>

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
                                <a href="./pages/profile.html">
                                    <img src="{{$seller->getFirstMediaUrl('special','webp')}}" alt="Story 2"/>
                                </a>
                                <div class="info">
                                    <p>name here</p>
                                    <a href="./pages/profile.html">
                                        <img src="{{$seller->getFirstMediaUrl('image','webp')}}" alt="avatar"/>
                                    </a>
                                </div>
                            </div>
                        @endforeach


                    </div>
                </div>

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
                        <a href="./pages/profile.html">
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

                                            {{$product->user?->seller_name}}</p>
                                        @if($product->type!=\App\Enums\CategoryTypeEnum::NEWS->value)
                                            <p class="d-block sub-title" style="text-align: right">
                                                {{$product->city?->name}} - {{$product->category?->name}}
                                                - {{$product->sub1?->name}}
                                            </p>
                                        @endif
                                    </div>
                                    <a href="./pages/profile.html">
                                        <img width="46" height="46" class="rounded-circle" src="{{$product->user?->getImage()}}" alt=""/>
                                    </a>
                                </div>
                            </div>

                            <div class="post-content " style="margin: 20px 0px 0px 0px">
                                <a  href="./pages/post-info.html">
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
                                    <a href="./pages/post-info.html">
                                        <img
                                            style="
                      width: 100%;
                      object-fit: cover;
                      margin: 5px 0px 0px 0px;
                    "
                                            src="{{$product->getImage('images')}}"
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
                                         style="width: 60px; height: 24px; padding: 5px; border-radius: 4px; color: #fff; background-color: #e60613; display: flex; align-items: center; justify-content: center; border: 5px;">
                                        @if($product->is_discount)
                                            <del class="text-secondary">{{$product->price}}</del>
                                            {{$product->discount}}

                                            @else
                                            {{$product->price}}
                                        @endif
                                        $
                                    </div>

                                        <form action="" method="POST" style="
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
                                            <input type="hidden" name="postId" value="123"/>
                                            <button type="button" onclick="addCart({{json_encode($product->toJson())}},{{json_encode($product->user?->toJson())}},'','')"
                                                    style="background-color: transparent; border: none; display: flex; align-items: center; gap: 8px; color: #fff;">
                                                <i class="bi bi-cart-fill"></i>
                                            </button>
                                        </form>


                                </div>
                                @endif

                            </div>

                            <div class="post-actions"
                                 style="margin: 20px 0px 0px 0px; padding: 0px 10px; display: flex; align-items: center; justify-content: space-between;">
                                <button
                                    class="copy-link"
                                    data-post-link="https://example.com/post/124"
                                    style="display: flex; align-items: center; gap: 8px; background-color: transparent;"
                                >
                                    <i style="font-size: 12px;" class="bi bi-share"></i>
                                    <p class="sub-title">مشاركة</p>
                                </button>
                                <button
                                    style="display: flex; align-items: center; gap: 8px; background-color: transparent;">
                                    <i style="font-size: 12px;" class="bi bi-eye"></i>
                                    <p class="sub-title">مشاهدات</p>
                                </button>
                                <form action="" method="POST"
                                      style="display: flex; align-items: center; gap: 8px; background-color: transparent;">
                                    <input type="hidden" name="postId" value="123"/>
                                    <button type="submit"
                                            style="background-color: transparent; border: none; display: flex; align-items: center; gap: 8px;">
                                        <i style="font-size: 12px;" class="bi bi-hand-thumbs-up"></i>
                                        <p class="sub-title">اعجاب</p>
                                    </button>
                                </form>
                                <a href="./pages/post-info.html">
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
                        <button
                            class="new-post"
                            data-bs-toggle="modal"
                            data-bs-target="#addPostModal"
                        >
                            منشور جديد
                        </button>
                    </div>
                    <div class="categories">
                        <p class="category-text">التصنيفات</p>
                        <div class="divider"></div>
                        @foreach($categories as $category)
                            <div class="category-item">
                                <p>{{$category->name}}</p>
                                <div class="count">{{$category->products_count}}</div>
                            </div>
                        @endforeach


                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection



