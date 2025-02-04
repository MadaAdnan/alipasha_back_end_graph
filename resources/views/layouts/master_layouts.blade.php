<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>ALI BASHA</title>
    <link rel="icon" type="image/png" href="./assets/logo.svg">
    <!-- Bootstrap CSS -->
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
        rel="stylesheet"
    />
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css"
        rel="stylesheet"
    />

    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{asset('assets/css/style.css')}}" />
    <link rel="stylesheet" href="{{asset('assets/css/shared.css')}}" />
</head>
<body>
<!-- nav bar  -->
<button id="goUpButton" class="btn btn-primary">
    <i class="bi bi-arrow-up"></i>
</button>
<nav class="navbar navbar-expand-lg fixed-top navbar-light bg-white d-px-3">
    <div class="container-fluid">
        <!-- Left Section: Icon/Brand -->
        <a class="navbar-brand" href="./index.html">
            <img
                src="{{asset('assets/logo.svg')}}"
                alt="Logo"
                class="d-inline-block align-text-top"
            />
        </a>

        <!-- Search Bar for Large Screens -->

            <form method="get"
                class="d-none d-md-flex align-items-center"
                style="
              background-color: #f0f2f5;
              height: 30px;
              border-radius: 40px;
              padding: 5px 10px;
            "
                  action="{{route('search.index')}}"
            >
                <i class="bi bi-search" style="margin-right: 8px; color: #aaa"></i>
                <input
                    class="search-nav form-control border-0 shadow-none"
                    type="search"

                    placeholder="ابحث في هذا المتجر"
                    aria-label="Search"
                    style="background-color: transparent; box-shadow: none"
                    name="q"
                />
                <img src="{{asset('assets/search-nav.svg')}}" alt="" />
            </form>


        <!-- Center Section: Links and Search -->
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a
                        class="nav-link active"
                        aria-current="page"
                        href="./index.html"
                    >
                        <div style="display: flex; gap: 8px; align-items: center;">
                            <img src="{{asset('assets/home.svg')}}" alt="" />
                            <p class="sub-title d-lg-none"> الواجهة الرئيسية </p>
                        </div>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="./pages/services.html">
                        <div style="display: flex; gap: 8px; align-items: center;">
                            <img src="{{asset('assets/services.svg')}}" alt=""
                            />
                            <p class="sub-title d-lg-none"> خدمات </p>

                        </div>
                    </a>

                </li>
                <li class="nav-item">
                    <a class="nav-link" href="./pages/jobs.html">
                        <div style="display: flex; gap: 8px; align-items: center;">
                            <img src="{{asset('assets/jobs.svg')}}" alt=""
                            />
                            <p class="sub-title d-lg-none"> وظائف </p>
                        </div>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="./pages/porsa.html">
                        <div style="display: flex; gap: 8px; align-items: center;">
                            <img src="{{asset('assets/porsa.svg')}}" alt=""
                            />
                            <p class="sub-title d-lg-none"> مناقصات </p>
                        </div>
                    </a>
                </li>
            </ul>

            <!-- Search Bar for Small Screens -->
            <a href="./pages/search.html">
                <form
                    class="d-flex align-items-center mt-3 d-md-none mb-3"
                    style="
                 background-color: #f0f2f5;
                 height: 30px;
                 border-radius: 40px;
                 padding: 5px 10px;
               "
                >
                    <i class="bi bi-search" style="margin-right: 8px; color: #aaa"></i>
                    <input
                        class="search-nav form-control border-0 shadow-none"
                        type="search"
                        placeholder="ابحث في هذا المتجر"
                        aria-label="Search"
                        style="background-color: transparent; box-shadow: none"
                    />
                </form>
            </a>
        </div>

        <!-- Right Section: Icons -->
        <div class="d-lg-flex align-items-center gap-2">
            <a href="./pages/pricing.html">
                <button
                    class="btn"
                    style="
              background-color: black;
              color: white;
              width: 140px;
              font-size: 14px;
            "
                >
                    ترقية الحساب
                    <img style="width: 16px" src="{{asset('assets/upgrade-star.svg')}}" alt="" />
                </button>
            </a>
            <a href="./pages/cart.html">
                <img src="{{asset('assets/market.svg')}}" alt="" />
            </a>
            <span
                class="notification-icon"
                style="position: relative"
                onclick="toggleRightSidebar()"
            >
            <img src="{{asset('assets/notification.svg')}}" alt="Notification" />
            <span
                style="
                position: absolute;
                top: -3px;
                right: 4px;
                width: 8px;
                height: 8px;
                background-color: #e30613;
                border-radius: 8px;
                color: #fff;
              "
            >
            </span>
          </span>
            <a href="./pages/profile.html">
                <img src="{{asset('assets/avatar.svg')}}" alt="" />
            </a>
            <!-- Toggler for Mobile View -->
        </div>

        <button
            class="navbar-toggler"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#navbarNav"
            aria-controls="navbarNav"
            aria-expanded="false"
            aria-label="Toggle navigation"
        >
            <span class="navbar-toggler-icon"></span>
        </button>
    </div>
</nav>

@yield('content')

<!-- post modal -->
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
                <h5 class="modal-title" id="formModalLabel">إضافة منشور</h5>
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
                            نشر لمدة
                        </p>
                        <select
                            name="date"
                            class="form-select"
                            aria-label="Default select example"
                            style="text-align: right"
                        >
                            <option value="1" selected>نشر بدون مدة</option>
                            <option value="1">نشر لمدة ثلاث شهور</option>
                            <option value="2">نشر لمدة شهر واحد</option>
                            <option value="3">نشر لمدة 15 يوم</option>
                            <option value="4">نشر لمدة اسبوع</option>
                        </select>
                    </div>

                    <div style="width: 100% ;display: flex; justify-content: center; align-items: center; gap: 8px;" class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="flexSwitchCheckDefault">
                            <label class="form-check-label" style="font-size: 12px;" for="flexSwitchCheckDefault">التوفر بالمخزن</label>
                        </div>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="flexSwitchCheckChecked" checked>
                            <label class="form-check-label" style="font-size: 12px;" for="flexSwitchCheckChecked">إشترك بخدمة شحن علي باشا</label>
                        </div>
                    </div>

                    <div class="mb-3">
                        <p
                            for="descriptionInput"
                            class="form-label"
                            style="text-align: right; font-size: 12px;"
                        >
                            اسم المنتج
                        </p>
                        <input
                            name="product_name"
                            style="text-align: right; font-size: 12px;"
                            class="form-control"
                            id="descriptionInput"
                            placeholder="اسم المنتج"
                            required
                        ></input>
                    </div>


                    <div class="mb-3">
                        <p
                            for="descriptionInput"
                            class="form-label"
                            style="text-align: right; font-size: 12px;"
                        >
                            وصف المنتج
                        </p>
                        <textarea
                            name="description"
                            style="text-align: right; font-size: 12px;"
                            class="form-control"
                            id="descriptionInput"
                            rows="3"
                            placeholder="وصف المنتج"
                            required
                        ></textarea>
                    </div>

                    <div class="mb-3">
                        <p
                            for="descriptionInput"
                            class="form-label"
                            style="text-align: right; font-size: 12px;"
                        >
                            السعر بالدولار
                        </p>
                        <input
                            name="price"
                            style="text-align: right; font-size: 12px;"
                            class="form-control"
                            id="descriptionInput"
                            placeholder="السعر بالدولار"
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
                            السعر بعد الحسم
                        </p>
                        <input
                            name="after_discount"
                            style="text-align: right; font-size: 12px;"
                            class="form-control"
                            id="descriptionInput"
                            placeholder="السعر بالدولار"
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
                            رابط الفيدو (إختياري)
                        </p>
                        <input
                            name="youtube_url"
                            style="text-align: right; font-size: 12px;"
                            class="form-control"
                            id="descriptionInput"
                            placeholder="رابط الفيدو (إختياري)"
                        ></input>
                    </div>

                    <div class="mb-3">
                        <p
                            for="fileInput"
                            class="form-label"
                            style="text-align: right; font-size: 12px;"
                        >
                            إضافة صورة او عدة صور
                        </p>
                        <input
                            name="images"
                            type="file"
                            class="form-control"
                            id="fileInput"
                            accept="image/*"
                            multiple
                            required
                        />
                    </div>


                    <div class="mb-3">
                        <p
                            for="descriptionInput"
                            class="form-label"
                            style="text-align: right; font-size: 12px;"
                        >
                            ماهو تصنيف المنتج؟
                        </p>
                        <select
                            name="main-section"
                            class="form-select"
                            aria-label="Default select example"
                            style="text-align: right; font-size: 12px;"
                        >
                            <option value="1" selected>القسم الرئيسي</option>
                            <option value="1">قسم المنتجات</option>
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
                            <option value="1" selected>شاشة</option>
                        </select>
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


<!-- job modal -->
<div
    class="modal fade"
    id="addJobModal"
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

<!-- porsa modal -->
<div
    class="modal fade"
    id="addPorsaModal"
    tabindex="-1"
    aria-labelledby="formModalLabel"
    aria-hidden="true"
    dir="ltr"
>
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="formModalLabel">إضافة مناقصة</h5>
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
                            بداية التقديم
                        </p>
                        <input
                            name="deatile"
                            style="text-align: right; font-size: 12px;"
                            class="form-control"
                            id="descriptionInput"
                            placeholder="بداية التقديم"
                            type="date"
                            required
                        ></input>
                    </div>

                    <div class="mb-3">
                        <p
                            for="descriptionInput"
                            class="form-label"
                            style="text-align: right; font-size: 12px;"
                        >
                            نهاية التقديم
                        </p>
                        <input
                            name="deatile"
                            style="text-align: right; font-size: 12px;"
                            class="form-control"
                            id="descriptionInput"
                            placeholder="نهاية التقديم"
                            type="date"
                            required
                        ></input>
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
                            رابط التقديم
                        </p>
                        <input
                            name="service_url"
                            style="text-align: right; font-size: 12px;"
                            class="form-control"
                            id="descriptionInput"
                            placeholder="رابط التقديم"
                            required
                        ></input>
                    </div>

                    <div class="mb-3">
                        <p
                            for="descriptionInput"
                            class="form-label"
                            style="text-align: right; font-size: 12px;"
                        >
                            كود المناقصة
                        </p>
                        <input
                            name="service_url"
                            style="text-align: right; font-size: 12px;"
                            class="form-control"
                            id="descriptionInput"
                            placeholder="كود المناقصة"
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

<!-- update account  -->
<div
    class="modal fade"
    id="acceptAccount"
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
                    <h3 class="mb-4 text-center">مميزات توثيق الحساب</h3>
                    <li class="mb-3">
                        <i class="bi bi-check-circle-fill text-success me-2"></i>
                        علامة الحساب موثق - تميز حسابك كحساب رسمي يمثل الشركة أو
                        المنظمة.
                    </li>
                    <li class="mb-3">
                        <i class="bi bi-check-circle-fill text-success me-2"></i>
                        علامة المنتجات الموثوقة - تظهر على كل منتج أو منشور لك، مما يعزز
                        ثقة العملاء بالجودة.
                    </li>
                    <li class="mb-3">
                        <i class="bi bi-check-circle-fill text-success me-2"></i>
                        هوية بصرية فريدة - إمكانية اختيار هوية بصرية خاصة بالشركة تعكس
                        رسالتها وتميزها.
                    </li>
                    <li class="mb-3">
                        <i class="bi bi-check-circle-fill text-success me-2"></i>
                        معرض صور للأعمال - عرض صور لمنتجات وأعمال الشركة لإبراز جودة
                        الخدمات والمنتجات.
                    </li>
                    <li class="mb-3">
                        <i class="bi bi-check-circle-fill text-success me-2"></i>
                        روابط حسابات التواصل الاجتماعي - إضافة معرفات الشركة على
                        السوشيال ميديا لتسهيل الوصول إلى حساباتها الرسمية.
                    </li>
                    <li class="mb-3">
                        <i class="bi bi-check-circle-fill text-success me-2"></i>
                        وصف مختصر (Slogan) للشركة - إدراج وصف ملهم يعبر عن رؤية ورسالة
                        الشركة في ملفها الشخصي.
                    </li>
                </ul>
                <a
                    href="https://wa.me/1234567890"
                    target="_blank"
                    class="btn btn-success d-inline-flex align-items-center"
                >
                    <i class="bi bi-whatsapp me-2"></i>
                    تواصل عبر واتساب
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Content goes here -->
<script src="{{asset('assets/js/functions_cart.js')}}"></script>
<!-- Bootstrap JS and Popper.js -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
<script>
    // Get all navbar links
    const navLinks = document.querySelectorAll(".nav-link");

    // Loop through each link
    navLinks.forEach((link) => {
        // Check if the link's href matches the current URL
        if (link.href === window.location.href) {
            link.classList.add("active");
        } else {
            link.classList.remove("active");
        }
    });
</script>
<script>
    const list = [].slice.call(
        document.querySelectorAll('[data-bs-toggle="popover"]')
    );
    list.map((el) => {
        let opts = {
            animation: false,
        };
        if (el.hasAttribute("data-bs-content-id")) {
            opts.content = document.getElementById(
                el.getAttribute("data-bs-content-id")
            ).innerHTML;
            opts.html = true;
        }
        new bootstrap.Popover(el, opts);
    });
</script>
<script>
    function toggleLeftSidebar() {
        const sidebar = document.getElementById("left-sidebar");
        const body = document.body;

        // Toggle the "active" class on the sidebar
        sidebar.classList.toggle("active");

        // Check if the sidebar is active and toggle the "no-scroll" class on the body
        if (sidebar.classList.contains("active")) {
            body.classList.add("no-scroll");
        } else {
            body.classList.remove("no-scroll");
        }
    }
</script>
<script>
    function toggleRightSidebar() {
        const sidebar = document.getElementById("right-sidebar");
        const body = document.body;

        // Toggle the "active" class on the sidebar
        sidebar.classList.toggle("active");

        // Check if the sidebar is active and toggle the "no-scroll" class on the body
        if (sidebar.classList.contains("active")) {
            body.classList.add("no-scroll");
        } else {
            body.classList.remove("no-scroll");
        }
    }
</script>
<script>
    // Get the button
    const goUpButton = document.getElementById("goUpButton");

    // Show the button when the user scrolls down 100px
    window.onscroll = function () {
        if (document.body.scrollTop > 100 || document.documentElement.scrollTop > 100) {
            goUpButton.style.display = "block";
        } else {
            goUpButton.style.display = "none";
        }
    };

    // Scroll to the top when the button is clicked
    goUpButton.addEventListener("click", function () {
        window.scrollTo({
            top: 0,
            behavior: "smooth",
        });
    });
</script>

<script>
    function showToast(message) {
        const toast = document.getElementById("toast");
        toast.textContent = message;
        toast.style.display = "block";

        // Hide toast after 2 seconds
        setTimeout(() => {
            toast.style.display = "none";
        }, 2000);
    }

    // Handle Share Button
    document.querySelectorAll(".copy-link").forEach((button) => {
        button.addEventListener("click", function () {
            const postLink = this.getAttribute("data-post-link");

            // Copy the link to the clipboard
            navigator.clipboard.writeText(postLink).then(() => {
                showToast("Copy successfully");
            }).catch(() => {
                showToast("Failed to copy");
            });
        });
    });
</script>

</body>
</html>


