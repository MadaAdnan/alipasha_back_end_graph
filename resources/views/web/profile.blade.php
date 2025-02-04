@extends('layouts.master_layouts')

@section('content')
    <div class="container-fluid" style="margin-top: 70px">
        <div class="row">
            <div class="col-12" style="margin-top: 10px">
                <div
                    class="container"
                    style="
              background-color: #fff;
              border-radius: 16px;
              padding: 16px;
              display: flex;
              align-items: center;
              justify-content: center;
              flex-direction: column;
              gap: 16px;
            "
                >
                    <img
                        src="{{auth()->user()->getImage()}}"
                        alt="profile-img"
                        style="width: 120px; height: 120px; border-radius: 120px"
                    />
                    <div style="text-align: center">
                        <p style="color: #e30613; font-size: 22px">{{auth()->user()->name}}</p>
                        <p style="color: #aaa; font-size: 18px">
                            {{auth()->user()->address}}
                        </p>
                    </div>
                    <div
                        style="
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 8px;
              "
                    >
                        @if(auth()->user()->products_count >0)
                            <a href="./store.html">
                                <button
                                    data-bs-toggle="modal"
                                    data-bs-target="#acceptAccount"
                                    style="
                    color: #fff;
                    background-color: #0f5fc2;
                    font-size: 12px;
                    padding: 8px;
                    border-radius: 4px;
                    "
                                >
                                    عرض المتجر
                                </button>
                            </a>
                        @endif

                        @if(!auth()->user()->is_verified)
                            <button
                                data-bs-toggle="modal"
                                data-bs-target="#acceptAccount"
                                style="
                  color: #fff;
                  background-color: #0f5fc2;
                  font-size: 12px;
                  padding: 8px;
                  border-radius: 4px;
                "
                            >
                                توثيق الحساب
                            </button>
                        @else
                            <button
                                style="
                  color: #fff;
                  background-color: #0f5fc2;
                  font-size: 12px;
                  padding: 8px;
                  border-radius: 4px;
                "
                            >
                                الحساب موثق
                            </button>
                        @endif

                        <button
                            data-bs-toggle="modal"
                            data-bs-target="#updateAccountModal"
                            style="
                  color:#000000;
                  background-color: #e4e6eb;
                  font-size: 12px;
                  padding: 8px;
                  border-radius: 4px;
                "
                        >
                            تعديل الملف الشخصي
                        </button>

                    </div>

                    <div
                        style="
                display: flex;
                align-items: center;
                justify-content: center;
                width: 300px;
                gap: 8px;
              "
                    >
                        <button
                            data-bs-toggle="modal"
                            data-bs-target="#statisticAccount"
                            style="
                  color: #fff;
                  background-color: #e30613;
                  font-size: 12px;
                  padding: 8px;
                  border-radius: 4px;
                "
                        >
                            الإحصائيات
                        </button>

                         <button
                          style="
                            color: #000000;
                            background-color: #e4e6eb;
                            font-size: 12px;
                            padding: 8px;
                            border-radius: 4px;
                          "
                        >
                          الإعلانات الممولة
                        </button>
                        <button
                          style="
                            color: #000000;
                            background-color: #e4e6eb;
                            font-size: 12px;
                            padding: 8px;
                            border-radius: 4px;
                          "
                        >
                          المنتجات
                        </button>
                    </div>
                </div>
            </div>

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
        </div>
    </div>

    <!-- statistic modal  -->
    <div
        class="modal fade"
        id="statisticAccount"
        tabindex="-1"
        aria-labelledby="infoModalLabel"
        aria-hidden="true"
        dir="ltr"
    >
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="formModalLabel">توثيق الحساب</h5>
                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="Close"
                    ></button>
                </div>
                <div class="modal-body">
                    <ul class="list-unstyled" dir="rtl">
                        <h3 class="mb-4 text-center">الرصيد و الإحصاء</h3>
                        <div class="row">
                            <div
                                class="col-6 col-lg-4"
                                style="
                    border: 1px solid #ccc;
                    padding: 16px;
                    border-radius: 12px;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    flex-direction: column;
                    gap: 4px;
                  "
                            >
                                <p class="sub-title">{{auth()->user()->getTotalPoint()}}</p>
                                <p class="title">رصيد الننقاط</p>
                            </div>
                            <div
                                class="col-6 col-lg-4"
                                style="
                    border: 1px solid #ccc;
                    padding: 16px;
                    border-radius: 12px;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    flex-direction: column;
                    gap: 4px;
                  "
                            >
                                <p class="sub-title">{{auth()->user()->advices->count()}}</p>
                                <p class="title">عدد الإعلانات</p>
                            </div>
                            <div
                                class="col-6 col-lg-4"
                                style="
                    border: 1px solid #ccc;
                    padding: 16px;
                    border-radius: 12px;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    flex-direction: column;
                    gap: 4px;
                  "
                            >
                                <p class="sub-title">{{auth()->user()->followers_count}}</p>
                                <p class="title">عدد المتابعين</p>
                            </div>
                            <div
                                class="col-6 col-lg-4"
                                style="
                    border: 1px solid #ccc;
                    padding: 16px;
                    border-radius: 12px;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    flex-direction: column;
                    gap: 4px;
                  "
                            >
                                <p class="sub-title">{{auth()->user()->total_views}}</p>
                                <p class="title">المشاهدات</p>
                            </div>
                            <div
                                class="col-6 col-lg-4"
                                style="
                    border: 1px solid #ccc;
                    padding: 16px;
                    border-radius: 12px;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    flex-direction: column;
                    gap: 4px;
                  "
                            >
                                <p class="sub-title">{{auth()->user()->getTotalBalance()}}</p>
                                <p class="title">الرصيد الحالي</p>
                            </div>
                           {{-- <div
                                class="col-6 col-lg-4"
                                style="
                    border: 1px solid #ccc;
                    padding: 16px;
                    border-radius: 12px;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    flex-direction: column;
                    gap: 4px;
                  "
                            >
                                <p class="sub-title">0.0</p>
                                <p class="title">مسحوبات الارباح</p>
                            </div>--}}
                        </div>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <!-- update account modal -->
    <div
        class="modal fade"
        id="updateAccountModal"
        tabindex="-1"
        aria-labelledby="formModalLabel"
        aria-hidden="true"
        dir="ltr"
    >
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="formModalLabel">تعديل الحساب</h5>
                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="Close"
                    ></button>
                </div>
                <div class="modal-body">
                    <form id="modalForm" action="{{route('profile.store')}}" method="post">
                        @csrf
                        @method('post')
                        <div class="mb-3">
                            <p
                                for="descriptionInput"
                                class="form-label"
                                style="text-align: right; font-size: 12px;"
                            >
                                <span style="color: #e30613;"> * </span> الإسم
                            </p>
                            <input
                                name="name"
                                style="text-align: right; font-size: 12px;"
                                value="{{auth()->user()->name}}"
                                class="form-control"
                                id="descriptionInput"
                                placeholder="الإسم"
                                required
                            />
                        </div>

                        <div class="mb-3">
                            <p
                                for="descriptionInput"
                                class="form-label"
                                style="text-align: right; font-size: 12px;"
                            >
                                <span style="color: #e30613;"> * </span> اسم المتجر
                            </p>
                            <input
                                name="store_name"
                                style="text-align: right; font-size: 12px;"
                                class="form-control"
                                id="descriptionInput"
                                value="{{auth()->user()->seller_name}}"
                                placeholder="اسم المتجر"
                                required
                            />
                        </div>

                        <div class="mb-3">
                            <p
                                for="descriptionInput"
                                class="form-label"
                                style="text-align: right; font-size: 12px;"
                            >
                                البريد الالكتروني
                            </p>
                            <input

                                name="email"
                                style="text-align: right; font-size: 12px;"
                                class="form-control"
                                id="descriptionInput"
                                placeholder="البريد الاكلتروني"
                                value="{{auth()->user()->email}}"
                                disabled
                            ></input>
                        </div>

                        <div class="mb-3">
                            <p
                                for="descriptionInput"
                                class="form-label"
                                style="text-align: right; font-size: 12px; color: #e30613;"
                            >
                                <span style="color: #e30613;"> * </span> أدخل رقم الهاتف مع رمز الدولة بدون + أو 00
                            </p>
                            <input
                                name="phone"
                                style="text-align: right; font-size: 12px;"
                                class="form-control"
                                id="descriptionInput"
                                placeholder="أدخل رقم الهاتف"
                                type="number"
                                value="{{auth()->user()->phone}}"
                                required
                            ></input>
                        </div>

                        <div class="mb-3">
                            <p
                                for="descriptionInput"
                                class="form-label"
                                style="text-align: right; font-size: 12px;"

                            >
                                كود الإحالة
                            </p>
                            <input
                                name="youtube_url"
                                style="text-align: right; font-size: 12px;"
                                class="form-control"
                                id="descriptionInput"
                                placeholder="كود الإحالة"
                                value="{{auth()->user()->affiliate}}"
                                disabled

                            />
                        </div>


                        <div class="mb-3">
                            <p
                                for="city_id"
                                class="form-label"
                                style="text-align: right; font-size: 12px;"
                            >
                                <span style="color: #e30613;"> * </span> المدينة
                            </p>
                            <select
                                name="city_id"
                                class="form-select"
                                aria-label="Default select example"
                                style="text-align: right"
                                required
                            >
                                <option value="">حدد مدينتك</option>
                                @foreach($cities as $city)
                                    <option @if($city->id==auth()->user()->city_id) selected
                                            @endif value="{{$city->id}}">{{$city->name}}</option>
                                @endforeach

                            </select>
                        </div>

                        {{--  <div class="mb-3">
                              <p
                                  for="city"
                                  class="form-label"
                                  style="text-align: right; font-size: 12px;"
                              >
                                  <span style="color: #e30613;"> * </span>   المدينة
                              </p>
                              <select
                                  name="date"
                                  class="form-select"
                                  aria-label="Default select example"
                                  style="text-align: right"
                              >
                                  <option value="1" selected>سرمدا</option>
                              </select>
                          </div>--}}


                        <div class="mb-3">
                            <p
                                for="descriptionInput"
                                class="form-label"
                                style="text-align: right; font-size: 12px;"

                            >
                                <span style="color: #e30613;"> * </span> العنوان التفصيلي
                            </p>
                            <input
                                name="address"
                                style="text-align: right; font-size: 12px;"
                                class="form-control"
                                id="descriptionInput"
                                placeholder="العنوان التفصيلي"
                                required
                                value="{{auth()->user()->address}}"
                            />
                        </div>


                        {{--<div class="mb-3">
                            <p
                                for="descriptionInput"
                                class="form-label"
                                style="text-align: right; font-size: 12px;"

                            >
                                <span style="color: #e30613;"> * </span>   يفتح الساعة
                            </p>
                            <input
                                name="discription"
                                style="text-align: right; font-size: 12px;"
                                class="form-control"
                                id="descriptionInput"
                                placeholder="العنوان التفصيلي"
                                type="time"
                                required
                            />
                        </div>
--}}
                        {{--<div class="mb-3">
                            <p
                                for="descriptionInput"
                                class="form-label"
                                style="text-align: right; font-size: 12px;"

                            >
                                <span style="color: #e30613;"> * </span>  يغلق الساعة
                            </p>
                            <input
                                name="discription"
                                style="text-align: right; font-size: 12px;"
                                class="form-control"
                                id="descriptionInput"
                                placeholder="العنوان التفصيلي"
                                type="time"
                                required
                            ></input>
                        </div>--}}


                        <div class="modal-footer">
                            <button
                                type="button"
                                class="btn btn-secondary"
                                data-bs-dismiss="modal"
                            >
                                اغلاق
                            </button>
                            <button type="submit" class="btn btn-primary">
                                تعديل
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
