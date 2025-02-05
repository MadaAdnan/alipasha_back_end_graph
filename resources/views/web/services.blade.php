@extends('layouts.master_layouts')

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
                                    <img src="../assets/avatar-2.svg" alt="avatar" />
                                </a>
                                <p class="title">احمد خالد المحمد يطلب منتجات</p>
                            </div>
                            <p class="time">4H</p>
                        </div>
                        <div class="actions">

                            <form action="" method="POST" style="width: 100%;">
                                <input type="hidden" name="storId" value="123" />
                                <button type="submit" class="btn btn-danger"
                                        class="action-buttons"
                                        style="color: #fff; background-color: #e30613"
                                >   قبول الطلب </button>
                            </form>


                            <form action="" method="POST" style="width: 100%;">
                                <input type="hidden" name="storId" value="123" />
                                <button type="submit" class="btn btn-danger"
                                        class="action-buttons"
                                        style="color: #000000; background-color: #e4e6eb"
                                >   عرض  الطلبية
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
                                    <img src="../assets/avatar-2.svg" alt="avatar" />
                                </a>
                                <p class="title">عبادة كحلوس</p>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <div class="col-12 col-xl-9" style="margin-top: 10px">
                <div class="container statistic">
                    <div class="statistic-item">
                        <div class="statistic-icon">
                            <img src="{{asset('assets/user-statistic-view.svg')}}" alt="" />
                        </div>
                        <div>
                            <p class="sub-title">المزودين بالمعلومات</p>
                            <p class="title">{{$sellers}}</p>
                        </div>
                    </div>
                    <div class="statistic-item">
                        <div class="statistic-icon">
                            <img src="{{asset('assets/user-statistic-view.svg')}}" alt="" />
                        </div>
                        <div>
                            <p class="sub-title">عدد المشاهدات</p>
                            <p class="title">{{$views}}</p>
                        </div>
                    </div>
                    <div class="statistic-item">
                        <div class="statistic-icon">
                            <img src="{{asset('assets/user-statistic-view.svg')}}" alt="" />
                        </div>
                        <div>
                            <p class="sub-title">الخدمات المنشورة</p>
                            <p class="title">{{$services_count}}</p>
                        </div>
                    </div>
                </div>

                <div class="table-wrapper container mt-1">

                    <!-- table filters -->
                    <div class="container mt-3 mb-3">
                        <div class="row">


                            <div class="col-span-12 col-lg-9">



                                <form action="">
                                    <div class="row">
                                        <div class="col-md-2 col-md-12 col-lg-3">
                                            <select name="city" class="form-select" aria-label="محافظة" style="
                        background-color: #f0f2f5;
                        height: 30px;
                        border-radius: 40px;
                        padding: 5px 30px;
                        font-size: 12px;
                        margin-bottom: 4px;
                      ">
                                                <option  value="" selected>المحافظة : الكل</option>
                                                @foreach($cities as $city)
                                                    <option value="{{$city->id}}">{{$city->name}}</option>
                                                @endforeach


                                                <!-- Add more provinces as needed -->
                                            </select>
                                        </div>

                                      {{--  <div class="col-md-2 col-md-12 col-lg-3">

                                            <select class="form-select" aria-label="المنطقة" style="
                        background-color: #f0f2f5;
                        height: 30px;
                        border-radius: 40px;
                        padding: 5px 30px;
                        font-size: 12px;
                        margin-bottom: 4px;
                      ">
                                                <option value="all" value="1" selected>المنطقة : الكل</option>
                                                <option value="area1">المنطقة 1</option>
                                                <option value="area2">المنطقة 2</option>
                                                <!-- Add more areas as needed -->
                                            </select>
                                        </div>--}}


                                        <div class="col-md-2 col-md-12 col-lg-3">
                                            <button type="submit" class="btn" style="background-color: #F2F3F4; color: #000;margin-bottom: 4px;"> تطبيق </button>
                                        </div>
                                    </div>
                                </form>
                            </div>



                            <div class="col-md-12 col-lg-3">
                                <form action="{{route('services.index')}}" method="get">
                                    <div
                                        class="d-flex align-items-center"
                                        style="
                      background-color: #f0f2f5;
                      height: 30px;
                      border-radius: 40px;
                      padding: 5px 10px;
                      margin-bottom: 4px;
                    "
                                    >
                                        <input
                                            name="q"
                                            class="search-nav form-control border-0 shadow-none"
                                            type="search"
                                            placeholder="بحث"
                                            aria-label="Search"
                                            style="background-color: transparent; box-shadow: none"
                                        />
                                        <button type="submit">
                                            <i class="bi bi-search" style="margin-right: 8px; color: #aaa"></i>
                                        </button>
                                    </div>

                                </form>
                            </div>


                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-borderless">
                            <thead>
                            <tr>
                                <th>استعراض</th>
                                <th>المنطقة</th>
                                <th>العنوان</th>
                                <th>اسم الخدمة</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($services as $service)
                                <tr>
                                    <td>
                                        <a href="{{route('services.show',$service->id)}}">
                                            <button
                                                class="btn"
                                                style="background: #00b087; color: #fff"
                                            >
                                                استعراض
                                            </button>
                                        </a>
                                    </td>
                                    <td>{{$service->city?->name}}</td>
                                    <td>{{$service->address}}</td>
                                    <td>{{$service->name}}</td>
                                </tr>
                            @endforeach


                            </tbody>
                        </table>
                    </div>


                    <div class="d-flex justify-content-between">
                        <a class="btn btn-sm btn-secondary" href="{{$services->nextPageUrl()}}">التالي</a>
                        <a class="btn btn-sm btn-secondary" href="{{$services->previousPageUrl()}}">السابق</a>
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
