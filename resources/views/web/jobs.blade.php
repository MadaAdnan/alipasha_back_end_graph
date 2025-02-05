@extends('layouts.master_layouts')

@section('content')
    <div class="container-fluid" style="margin-top: 70px">
        <div class="row">

            <div class="col-12 col-xl-9" style="margin-top: 10px">
                <div class="container statistic">
                    <div class="statistic-item">
                        <div class="statistic-icon">
                            <img src="{{asset('assets/user-statistic-view.svg')}}" alt="" />
                        </div>
                        <div>
                            <p class="sub-title">المزودين بالمعلومات</p>
                            <p class="title">5,423</p>
                        </div>
                    </div>
                    <div class="statistic-item">
                        <div class="statistic-icon">
                            <img src="{{asset('assets/user-statistic-view.svg')}}" alt="" />
                        </div>
                        <div>
                            <p class="sub-title">عدد المشاهدات</p>
                            <p class="title">{{$jobs->sum('total_views')}}</p>
                        </div>
                    </div>
                    <div class="statistic-item">
                        <div class="statistic-icon">
                            <img src="{{asset('assets/user-statistic-view.svg')}}" alt="" />
                        </div>
                        <div>
                            <p class="sub-title">الخدمات المنشورة</p>
                            <p class="title">189</p>
                        </div>
                    </div>
                </div>

                <div class="table-wrapper container mt-1">
                    <!-- table filters -->
                    <div class="container mt-3 mb-3">
                        <div class="row">


                            <div class="col-span-12 col-lg-9">



                                <form action="{{route('jobs.index')}}" method="get">
                                    <div class="row">
                                     {{--   <div class="col-md-2 col-md-12 col-lg-3">
                                            <select class="form-select" aria-label="محافظة" style="
                            background-color: #f0f2f5;
                            height: 30px;
                            border-radius: 40px;
                            padding: 5px 30px;
                            font-size: 12px;
                            margin-bottom: 4px;
                          ">
                                                <option  value="1" selected>المحافظة : الكل</option>
                                                <option value="province1">محافظة 1</option>
                                                <option value="province2">محافظة 2</option>
                                                <!-- Add more provinces as needed -->
                                            </select>
                                        </div>--}}

                                        <div class="col-md-2 col-md-12 col-lg-3">

                                            <select class="form-select" name="town" aria-label="المنطقة" style="
                            background-color: #f0f2f5;
                            height: 30px;
                            border-radius: 40px;
                            padding: 5px 30px;
                            font-size: 12px;
                            margin-bottom: 4px;
                          ">
                                                <option  value="" selected>المنطقة : الكل</option>
                                               @foreach($cities as $city)
                                                    <option value="{{$city->id}}">{{$city->name}}</option>
                                               @endforeach


                                                <!-- Add more areas as needed -->
                                            </select>
                                        </div>

                                        <div class="col-md-2 col-md-12 col-lg-3" >
                                            <select class="form-select" name="type" aria-label="النوع"     style="
                            background-color: #f0f2f5;
                            height: 30px;
                            border-radius: 40px;
                            padding: 5px 30px;
                            font-size: 12px;
                            margin-bottom: 4px;
                          ">
                                                <option  value="" selected>النوع : الكل</option>
                                                <option value="search_job">يبحث عن وظيفة</option>
                                                <option value="job">شاغر</option>
                                                <!-- Add more types as needed -->
                                            </select>
                                        </div>

                                        <div class="col-md-2 col-md-12 col-lg-3">
                                            <button class="btn" type="submit" style="background-color: #F2F3F4; color: #000;margin-bottom: 4px;"> تطبيق </button>
                                        </div>
                                    </div>
                                </form>
                            </div>



                            <div class="col-md-12 col-lg-3">
                                <form action="{{route('jobs.index')}}" method="get">
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
                                <th>آخر موعد للتقديم</th>
                                <th>التصنيف</th>
                                <th>النوع</th>
                                <th>الناشر</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($jobs as $job)
                                <tr>
                                    <td>
                                        <a href="{{route('jobs.show',$job->id)}}">
                                            <button
                                                class="btn"
                                                style="background: #00b087; color: #fff"
                                            >
                                                استعراض
                                            </button>
                                        </a>
                                    </td>
                                    <td>{{$job->city?->name}}</td>
                                    <td>{{$job->start_date->format('Y-m-d')}}</td>
                                    <td>الوظائف الإدارية</td>
                                    <td>
                                        <button
                                            class="btn"
                                            style="background: #ff8f13; color: #fff"
                                        >
                                          @if($job->type=='job')
                                                شاغر
                                              @else
                                                يبحث عن عمل
                                            @endif
                                        </button>
                                    </td>
                                    <td>{{$job->user?->seller_name}}</td>
                                </tr>
                            @endforeach


                            </tbody>
                        </table>
                    </div>
                    <div class="d-flex justify-content-between">
                        <a class="btn btn-sm btn-secondary" href="{{$jobs->nextPageUrl()}}">التالي</a>
                        <a class="btn btn-sm btn-secondary" href="{{$jobs->previousPageUrl()}}">السابق</a>
                    </div>
                   {{-- <div class="pagination-wrapper">
                        <nav aria-label="Page navigation">
                            <ul class="pagination">
                                <li class="page-item">
                                    <a class="page-link" href="#">
                                        <i class="bi bi-chevron-right"></i>
                                    </a>
                                </li>
                                <li class="page-item active" aria-current="page">
                                    <a class="page-link" href="#">1</a>
                                </li>
                                <li class="page-item"><a class="page-link" href="#">2</a></li>
                                <li class="page-item"><a class="page-link" href="#">3</a></li>

                                <li class="page-item disabled">
                                    <a class="page-link" href="#" tabindex="-1" aria-disabled="true">
                                        <i class="bi bi-chevron-left"></i>
                                    </a>
                                </li>
                            </ul>
                        </nav>
                        <p style="font-size: 14px; font-weight: 500; color: #B5B7C0;"> Showing data 1 to 8 of  256K entries </p>

                    </div>--}}
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
                        <button class="new-post"     data-bs-toggle="modal"
                                data-bs-target="#addPostModal">أضف وظيفة غير متوفرة</button>
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
    <!-- job modal -->
    <div
        class="modal fade"
        id="addPostModal"
        tabindex="-1"
        aria-labelledby="formModalLabel"
        aria-hidden="true"
        dir="ltr"
    >
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="formModalLabel">إضافة وظيفة</h5>
                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="Close"
                    ></button>
                </div>
                <div class="modal-body">
                    <form id="modalForm">


                        <div style="width: 100% ;display: flex; justify-content: center; align-items: center; gap: 8px;" class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="flexRadioDefault" id="flexRadioDefault1">
                                <label class="form-check-label" for="flexRadioDefault1">
                                    شاغر وظيفي
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="flexRadioDefault" id="flexRadioDefault2" checked>
                                <label class="form-check-label" for="flexRadioDefault2">
                                    أبحث عن وظيفة
                                </label>
                            </div>
                        </div>

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
                                name="number"
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
