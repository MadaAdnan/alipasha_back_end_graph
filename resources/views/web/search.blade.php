@extends('layouts.master_layouts')

@section('content')
    <div class="container-fluid" style="margin-top: 70px">
        <div class="row">



            <div class="col-12 col-xl-9" style="margin-top: 10px">
                <div class="container">

                    <form action="" style="background: #fff; padding: 16px; border-radius: 16px;">
                        <div  class="search-form">
                            <form action="{{route('search.index')}}">
                                <div style="width: 100% ;display: flex; justify-content: center; align-items: center; gap: 8px;" class="mb-3">
                                    <div style="display: flex; gap: 8px; flex-wrap: wrap;">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="type" id="منتج">
                                            <label class="form-check-label" for="منتج">
                                                منتج
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="type" id="متجر" checked>
                                            <label class="form-check-label" for="متجر">
                                                متجر
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="type" id="وظائف" checked>
                                            <label class="form-check-label" for="وظائف">
                                                وظائف
                                            </label>
                                        </div>

                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="type" id="مناقصات" checked>
                                            <label class="form-check-label" for="مناقصات">
                                                مناقصات
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="type" id="خدمات" checked>
                                            <label class="form-check-label" for="خدمات">
                                                خدمات
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="type" id="اخبار" checked>
                                            <label class="form-check-label" for="اخبار">
                                                اخبار
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <input
                                        name="q"
                                        style="text-align: right; font-size: 12px;"
                                        class="form-control"
                                        id="descriptionInput"
                                        placeholder="بحث"
                                        required
                                    ></input>
                                </div>
                                <div class="mb-3">
                                    <p
                                        for="city"
                                        class="form-label"
                                        style="text-align: right; font-size: 12px;"
                                    >
                                        المدينة
                                    </p>
                                    <select
                                        name="city"
                                        class="form-select"
                                        aria-label="Default select example"
                                        style="text-align: right"
                                    >
                                        <option value="" @if(request()->get('city')==null) selected @endif></option>
                                        @foreach($cities as $city)
                                            <option value="{{$city->id}}" @if(request()->get('city')==$city->id) selected @endif>{{$city->name}}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <p
                                        for="sections"
                                        class="form-label"
                                        style="text-align: right; font-size: 12px;"
                                    >
                                        إختر التصنيف
                                    </p>
                                    <select
                                        name="category"
                                        class="form-select"
                                        aria-label="Default select example"
                                        style="text-align: right"
                                    >
                                        <option value="" @if(request()->get('category')==null) selected @endif></option>
                                        @foreach($categories as $category)
                                            <option value="{{$category->id}}" @if(request()->get('category')==$category->id) selected @endif>{{$category->name}}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <p
                                        for="sections"
                                        class="form-label"
                                        style="text-align: right; font-size: 12px;"
                                    >
                                        إختر القسم
                                    </p>
                                    <select
                                        name="section"
                                        class="form-select"
                                        aria-label="Default select example"
                                        style="text-align: right"
                                    >
                                        <option value="1" selected>عام</option>=
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="rangeInput" class="form-label">اختر قيمة بين 0 و 10,000</label>
                                    <input
                                        type="range"
                                        class="form-range"
                                        id="rangeInput"
                                        min="0"
                                        max="10000"
                                        step="1"
                                        value="5000"
                                        oninput="updateValue(this.value)">
                                    <div class="mt-2">
                                        <span>القيمة الحالية: </span>
                                        <span id="rangeValue">5000</span>
                                    </div>
                                </div>

                                <button type="submit" class="btn" style="color: #fff; width: 100%; background-color: #e30613;"> بحث </button>

                            </form>

                        </div>
                    </form>

                    <div style="background-color: #fff; padding: 16px; border-radius: 16px; margin: 20px 0px; display: flex; flex-wrap: wrap;gap: 16px;">
                        <div class="card" style="width: 18rem; text-align: right;">
                            <img src="../assets/stor-avatar.svg" class="card-img-top" alt="...">
                            <div class="card-body">
                                <h5 class="card-title">الاسم هنا</h5>
                                <p class="card-text">الوصف هنا</p>
                                <p class="card-text">الموقع هنا</p>
                                <a href="./store.html" class="btn btn-primary"> زيارة </a>
                            </div>
                        </div>
                        <div class="card" style="width: 18rem; text-align: right;">
                            <img src="../assets/stor-avatar.svg" class="card-img-top" alt="...">
                            <div class="card-body">
                                <h5 class="card-title">الاسم هنا</h5>
                                <p class="card-text">الوصف هنا</p>
                                <p class="card-text">الموقع هنا</p>
                                <a href="./store.html" class="btn btn-primary"> زيارة </a>
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
