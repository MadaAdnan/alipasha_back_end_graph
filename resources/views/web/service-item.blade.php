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
                                   {{-- <form action="">
                                        <input type="hidden" name="free" value="123">
                                        <button type="submit" class="btn" style="width: 100%; background-color: #e30613; color: #fff; margin: 20px 0px;">إبلاغ عن الخدمة</button>
                                    </form>--}}
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
    <!-- service modal -->
    <div
        class="modal fade"
        id="addServiceModal"
        tabindex="-1"
        aria-labelledby="formModalLabel"
        aria-hidden="true"
        dir="ltr"
    >
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="formModalLabel">إضافة خدمة</h5>
                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="Close"
                    ></button>
                </div>
                <div class="modal-body">
                    <form id="modalForm">

                        <div class="mb-3">
                            <p
                                for="descriptionInput"
                                class="form-label"
                                style="text-align: right; font-size: 12px;"
                            >
                                الوصف
                            </p>
                            <textarea
                                name="description"
                                style="text-align: right; font-size: 12px;"
                                class="form-control"
                                id="descriptionInput"
                                rows="3"
                                placeholder="الوصف"
                                required
                            ></textarea>
                        </div>

                        <div class="mb-3">
                            <p
                                for="descriptionInput"
                                class="form-label"
                                style="text-align: right; font-size: 12px;"
                            >
                                العنوان التفصيلي
                            </p>
                            <input
                                name="deatile"
                                style="text-align: right; font-size: 12px;"
                                class="form-control"
                                id="descriptionInput"
                                placeholder="العنوان التفصيلي"
                                required
                            ></input>
                        </div>

                        <div class="mb-3">
                            <p
                                for="descriptionInput"
                                class="form-label"
                                style="text-align: right; font-size: 12px;"
                            >
                                البريد الإلكتروني
                            </p>
                            <input
                                name="email"
                                style="text-align: right; font-size: 12px;"
                                class="form-control"
                                id="descriptionInput"
                                placeholder="البريد الإلكتروني"
                                required
                            ></input>
                        </div>

                        <div class="mb-3">
                            <p
                                for="descriptionInput"
                                class="form-label"
                                style="text-align: right; font-size: 12px;"

                            >
                                رقم الهاتف
                            </p>
                            <input
                                name="nmber"
                                style="text-align: right; font-size: 12px;"
                                class="form-control"
                                id="descriptionInput"
                                placeholder="رقم الهاتف"
                                type="number"
                                required
                            ></input>
                        </div>

                        <div class="mb-3">
                            <p
                                for="descriptionInput"
                                class="form-label"
                                style="text-align: right; font-size: 12px;"
                            >
                                رابط الخدمة
                            </p>
                            <input
                                name="service_url"
                                style="text-align: right; font-size: 12px;"
                                class="form-control"
                                id="descriptionInput"
                                placeholder="رابط الخدمة"
                                required
                            ></input>
                        </div>

                        <div class="mb-3">
                            <p
                                for="descriptionInput"
                                class="form-label"
                                style="text-align: right; font-size: 12px;"
                            >
                                القسم الرئيسي
                            </p>
                            <select
                                name="main-section"
                                class="form-select"
                                aria-label="Default select example"
                                style="text-align: right; font-size: 12px;"
                            >
                                <option value="1" selected>القسم الرئيسي</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <p
                                for="descriptionInput"
                                class="form-label"
                                style="text-align: right; font-size: 12px;"
                            >
                                القسم الفرعي
                            </p>
                            <select
                                name="sub-section"
                                class="form-select"
                                aria-label="Default select example"
                                style="text-align: right; font-size: 12px;"
                            >
                                <option value="1" selected>وظائف عمل الانتاج</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <p
                                for="fileInput"
                                class="form-label"
                                style="text-align: right; font-size: 12px;"
                            >
                                إضافة مرفقات
                            </p>
                            <input
                                type="file"
                                class="form-control"
                                id="fileInput"
                                accept="image/*"
                                multiple
                                required
                            />
                        </div>

                        <div class="modal-footer">
                            <button
                                type="button"
                                class="btn btn-secondary"
                                data-bs-dismiss="modal"
                            >
                                اغلاق
                            </button>
                            <button type="submit" class="btn btn-primary">
                                إضافة
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
