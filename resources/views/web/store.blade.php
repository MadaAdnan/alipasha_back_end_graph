@extends('layouts.master_layouts')

@section('title')
    {{$store->seller_name}}
@endsection
@section('content')
    <div class="container-fluid" style="margin-top: 70px">
        <div class="row">

            <!-- Right Section (2 columns on large screens, 0 on small) -->
            <div id="right-others-sidebar" class="col-3 d-none">
                <div class="media-scroll bg-light p-4 h-100">
                    <div class="inbox">
                        <p class="title">صندوق الوارد</p>
                        <ul>
                            <li><a href=""> إظهار الكل </a></li>
                            <li><a href="">مبيعاتي</a></li>
                            <li><a href="">مشترياتي</a></li>
                            <li><a href="">الإشعارات</a></li>
                        </ul>
                    </div>

                    <div class="notification-item">
                        <div class="info">
                            <div>
                                <a href="./profile.html">
                                    <img src="../assets/avatar-2.svg" alt="avatar"/>
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
                                <a href="./profile.html">
                                    <img src="../assets/avatar-2.svg" alt="avatar"/>
                                </a>
                                <p class="title">عبادة كحلوس</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-xl-9" style="margin-top: 10px">
                <div class="container">
                    <div style="width: 100%; height: auto; position: relative">
                        <img
                            src="{{$store->getImage('logo')}}"
                            style="width: 100%; height: auto; max-height: 600px; object-fit: cover"
                            alt=""
                        />
                        <div class="stor-info">
                            <div class="stor-statictis">
                                <div
                                    style="
                      display: flex;
                      justify-content: space-between;
                      align-items: center;
                      gap: 8px;
                    "
                                >
                                    <div style="display: flex; flex-direction: column">
                                        <p
                                            style="
                          font-size: 36px;
                          color: #e30613;
                          font-weight: 600;
                        "
                                        >
                                            {{$store->followers_count}}
                                        </p>
                                        <p class="sub-title">يتابعه</p>
                                    </div>
                                    <div style="display: flex; flex-direction: column">
                                        <p
                                            style="
                          font-size: 36px;
                          color: #e30613;
                          font-weight: 600;
                        "
                                        >
                                            @if($store->total_views<=1000 )
                                                {{$store->total_views}}
                                                @elseif($store->total_views>1000 && $store->total_views<=1000000 )
                                                {{sprintf('%.1f', $store->total_views/1000)}} K
                                            @elseif($store->total_views>1000000)
                                                {{sprintf('%.1f', $store->total_views/1000000)}} M
                                                @endif
                                        </p>
                                        <p class="sub-title">مشاهدات</p>
                                    </div>
                                    <div style="display: flex; flex-direction: column">
                                        <p
                                            style="
                          font-size: 36px;
                          color: #e30613;
                          font-weight: 600;
                        "
                                        >
                                            {{$store->following_count}}
                                        </p>
                                        <p class="sub-title">متابعين</p>
                                    </div>
                                </div>
                                <div class="actions" style="margin-top: 10px">
                                    <a href="./business-gallery.html" class="btn btn-danger">

                                        معرض الأعمال
                                    </a>
                                    <button
                                        class="btn btn-danger"
                                        data-bs-toggle="modal"
                                        data-bs-target="#contact"
                                    >
                                        مراسلة التاجر
                                    </button>
                                </div>
                            </div>
                            <div class="stor-name">
                                <div class="text-infos">
                                    <p
                                        style="font-weight: 700; font-size: 24px; color: #e30613"
                                    >
                                        {{$store->seller_name}}
                                    </p>
                                    <p
                                        style="font-size: 18px; color: #544c4c; font-weight: 400"
                                    >
                                       {{$store->info}}
                                    </p>
                                    <p
                                        class="location"
                                        style="font-size: 18px; color: #544c4c; font-weight: 400"
                                    >
                                        <i class="bi bi-geo-alt-fill"></i> {{$store->address}}
                                    </p>
                                </div>
                                <img src=" {{$store->getImage()}}" style="width: 100px;
  border-radius: 50%;
  aspect-ratio: 1/1;
  object-fit: cover;" alt=""/>
                            </div>
                        </div>
                    </div>

                    <div class="stor-products">
                        @foreach($products as $product)
                            <div class="products">
                                <img src="{{$product->getImage()}}" alt=""/>
                                @if($product->is_discount)
                                <div class="type">عرض</div>
                                @elseif($product->level==\App\Enums\LevelProductEnum::SPECIAL->value)
                                    <div class="type">مميز</div>
                                @elseif($product->level==\App\Enums\LevelProductEnum::NEWS->value)
                                    <div class="type">جديد</div>
                                @endif
                                <p>{{$product->name}}</p>
                                <div
                                    style="
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                  "
                                >
                                    <p class="text-danger">
                                        @if($product->is_discount)
                                            {{$product->discount}} $ <del class="text-muted fs-6">{{$product->price}} $</del>
                                            @else
                                        {{$product->price}} $
                                        @endif
                                    </p>
                                    <form action="" method="POST"
                                          style="
                    cursor: pointer;
                    width: 18px;
                    height: 18px;
                    background-color: #e30613;
                    color: #fff;
                    border-radius: 4px;
                    font-size: 12px;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                  ">
                                        <input type="hidden" name="storId" value="123"/>
                                        <button type="submit"
                                                style="background-color: transparent; border: none; display: flex; align-items: center; gap: 8px; color: #fff;">
                                            <i class="bi bi-cart-fill"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach


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
                        <div class="category-item">
                            <p>مركز تدريب</p>
                            <div class="count">5</div>
                        </div>
                        <div class="category-item">
                            <p>مشافي</p>
                            <div class="count">12</div>
                        </div>
                        <div class="category-item">
                            <p>أفران</p>
                            <div class="count">5</div>
                        </div>
                        <div class="category-item">
                            <p>دور رعاية المسنين</p>
                            <div class="count">1</div>
                        </div>
                        <div class="category-item">
                            <p>مدارس</p>
                            <div class="count">7</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
