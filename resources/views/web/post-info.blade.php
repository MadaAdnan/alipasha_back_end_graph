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

                <div id="toast" style="position: fixed; bottom: 20px; right: 20px; background-color: #28a745; color: #fff; padding: 10px 20px; border-radius: 5px; display: none;">
                    Copy successfully
                </div>
                <div class="container">
                    <div class="posts">
                        <div class="post">
                            <div class="post-info-header">

                                <div style="display: flex; gap: 4px;">
                                    <form action="" method="POST" style="display: flex; align-items: center; gap: 8px; background-color: transparent;">
                                        <input type="hidden" name="storId" value="123" />
                                        <button type="submit" class="btn btn-danger"> متابعة </button>
                                    </form>


                                    <button        data-bs-toggle="modal"
                                                   data-bs-target="#contact" type="submit"  class="btn btn-danger"> مراسلة التاجر </button>
                                </div>


                                <div class="post-info">
                                    <div style="display: flex; gap: 4px; flex-direction: column">
                                        <p class="sub-title" style="text-align: right">  منشور بواسطة <i class="bi bi-geo-alt"></i> {{$post->city?->name}} </p>
                                        <p class="title" style="text-align: right">
                                            {{$post->user?->name}}
                                        </p>
                                    </div>
                                    <a href="../pages/profile.html">
                                        <img src="{{$post->user?->getImage()}}" alt="" />
                                    </a>
                                </div>
                            </div>

                            <div class="post-content" style="margin: 20px 0px 0px 0px">
                                <a href="../pages/post-info.html">
                                    <p class="title" style="text-align: right">
                                        معلومات عن المنشور
                                    </p>
                                    <p class="sub-title" style="text-align: right">{{$post->created_at?->diffForHumans()}}</p>
                                    <div class="pricing" style="display: flex; align-items: center; justify-content: space-around; margin: 10px 0px;">
                                        <p class="title" style="color: #e30613;"> {{$post->price}} $</p>
                                        {{--<p class="title" style="color: #e30613;"> €35.25 </p>--}}
                                        <p class="title" style="color: #e30613;">  {{$post->discount}} $  </p>
                                    </div>

                                </a>
                                <div
                                    style="
                        width: 100%;
                        height: 100%;
                        border-radius: 10px;
                        overflow: hidden;
                        margin: 10px 0px;
                      "
                                >
                                    <div id="carouselExampleInterval" class="carousel slide" data-bs-ride="carousel">
                                        <div class="carousel-indicators">
                                            @foreach($post->getImages() as $image)
                                            <button type="button" data-bs-target="#carouselExampleInterval" data-bs-slide-to="{{$loop->index}}" @if($loop->iteration==1) class="active" aria-current="true" @endif aria-label="Slide {{$loop->iteration}}"></button>
                                           {{-- <button type="button" data-bs-target="#carouselExampleInterval" data-bs-slide-to="1" aria-label="Slide 2"></button>
                                            <button type="button" data-bs-target="#carouselExampleInterval" data-bs-slide-to="2" aria-label="Slide 3"></button>--}}
                                            @endforeach
                                        </div>
                                        <div class="carousel-inner">
                                            @foreach($post->getImages() as $image)
                                                <div class="carousel-item active" data-bs-interval="10000">
                                                    <img src="{{$image}}" class="d-block w-100" alt="...">
                                                </div>
                                            @endforeach


                                        </div>
                                        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleInterval" data-bs-slide="prev">
                                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                            <span class="visually-hidden">Previous</span>
                                        </button>
                                        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleInterval" data-bs-slide="next">
                                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                            <span class="visually-hidden">Next</span>
                                        </button>
                                    </div>
                                </div>

                                <div style="display: flex; justify-content: end;margin: 20px 0px;">
                                    <div class="post-info">
                                        <div style="display: flex; gap: 4px; flex-direction: column">
                                            <p class="title" style="text-align: right">{{$post->user?->getImage()}}</p>
                                            <p class="sub-title" style="text-align: right">
                                               {{$post->city?->city?->name}} - {{$post->city?->name}} - {{$post->category?->name}}
                                            </p>
                                        </div>
                                        <a href="../pages/profile.html">
                                            <img src="../assets/avatar-2.svg" alt="" />
                                        </a>
                                    </div>
                                </div>

                            </div>

                            <div style="margin: 20px 0px 0px 0px; padding: 0px 10px; display: flex; align-items: center; justify-content: space-between;">
                                <div class="price" style="width: 90px; height: 24px; padding: 5px; border-radius: 4px; color: #fff; background-color: #aaa; display: flex; align-items: center; justify-content: center; border: 5px; font-size: 12px;">
                                    @if($post->is_delivery)
                                        متوفر شحن
                                    @else
                                        غير متوفر شحن
                                        @endif
                                     </div>
                                <div style="display: flex; gap: 8px;">
                                    <div class="price" style="width: 60px; height: 24px; padding: 5px; border-radius: 4px; color: #fff; background-color: #e60613; display: flex; align-items: center; justify-content: center; border: 5px;"> 6$ </div>
                                    <form action="" method="POST"  style="
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
                                        <input type="hidden" name="postId" value="123" />
                                        <button type="submit" style="background-color: transparent; border: none; display: flex; align-items: center; gap: 8px; color: #fff;">
                                            <i class="bi bi-cart-fill"></i>
                                        </button>
                                    </form>
                                </div>

                            </div>

                            <div class="post-actions" style="margin: 20px 0px 0px 0px; padding: 0px 10px; display: flex; align-items: center; justify-content: space-between;">
                                <button
                                    class="copy-link"
                                    data-post-link="https://example.com/post/123"
                                    style="display: flex; align-items: center; gap: 8px; background-color: transparent;"
                                >
                                    <i style="font-size: 12px;" class="bi bi-share"></i>
                                    <p class="sub-title">مشاركة</p>
                                </button>
                                <button style="display: flex; align-items: center; gap: 8px; background-color: transparent;">
                                    <i style="font-size: 12px;" class="bi bi-eye"></i>
                                    <p class="sub-title">مشاهدات</p>
                                </button>
                                <form action="" method="POST" style="display: flex; align-items: center; gap: 8px; background-color: transparent;">
                                    <input type="hidden" name="postId" value="123" />
                                    <button type="submit" style="background-color: transparent; border: none; display: flex; align-items: center; gap: 8px;">
                                        <i style="font-size: 12px;" class="bi bi-hand-thumbs-up"></i>
                                        <p class="sub-title">اعجاب</p>
                                    </button>
                                </form>
                            </div>


                            <div class="comments-section" style="margin-top: 20px; padding: 10px;">
                                <h5 style="text-align: right; margin-bottom: 10px;">التعليقات</h5>

                                <!-- Dummy Comments -->
                                <div class="comments-list" style="max-height: 200px; overflow-y: auto; margin-bottom: 15px;">
                                    <div class="comment" style="display: flex; align-items: flex-start; gap: 10px; margin-bottom: 10px;">
                                        <img src="../assets/avatar-2.svg" alt="Avatar" style="width: 40px; height: 40px; border-radius: 50%;">
                                        <div style="flex-grow: 1;">
                                            <p style="margin: 0; font-weight: bold;">محمد أحمد</p>
                                            <p style="margin: 0;">هذا منشور رائع! شكراً للمشاركة.</p>
                                        </div>
                                    </div>
                                    <div class="comment" style="display: flex; align-items: flex-start; gap: 10px; margin-bottom: 10px;">
                                        <img src="../assets/avatar-2.svg" alt="Avatar" style="width: 40px; height: 40px; border-radius: 50%;">
                                        <div style="flex-grow: 1;">
                                            <p style="margin: 0; font-weight: bold;">سارة علي</p>
                                            <p style="margin: 0;">معلومات مفيدة جداً، شكراً لك!</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Add Comment Form -->
                                <form action="" method="POST" style="display: flex; align-items: center; gap: 10px;">
                                    <input
                                        type="text"
                                        name="comment"
                                        placeholder="أضف تعليقاً..."
                                        class="form-control"
                                        style="flex-grow: 1;"
                                        required
                                    />
                                    <button type="submit" class="btn btn-primary" >
                                        <i class="bi bi-send"></i>
                                    </button>
                                </form>
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
@endsection
