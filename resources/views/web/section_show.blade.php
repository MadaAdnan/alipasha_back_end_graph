@extends('layouts.master_layouts')

@section('title')
    {{$category->name}}
@endsection
@section('content')
    <div class="container-fluid" style="margin-top: 70px">
        <div class="row">


            <div class="col-12 col-xl-9" style="margin-top: 10px">
                <div class="container">


                    <div class="stor-products">
                        @foreach($products as $product)
                            <a
                                @if($product->type==\App\Enums\CategoryTypeEnum::SEARCH_JOB->value || $product->type==\App\Enums\CategoryTypeEnum::JOB->value )

                                href="{{route('jobs.show',$product->id)}}"
                                @elseif($product->type==\App\Enums\CategoryTypeEnum::TENDER->value )
                                href="{{route('tenders.show',$product->id)}}"
                                @elseif($product->type==\App\Enums\CategoryTypeEnum::RESTAURANT->value ||  $product->type==\App\Enums\CategoryTypeEnum::PRODUCT->value )
                                href="{{route('posts.show',$product->id)}}"
                                @endif
                            >
                            <div class="products">
                                <img src=" @if($product->hasMedia('image')){{$product->getImage('image')}} @else {{$product->getImage('images')}}  @endif" alt=""/>
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
                            </a>
                        @endforeach


                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="d-flex justify-content-between">
                                @if($products->hasMorePages())
                                    <a class="btn btn-sm btn-secondary" href="{{$products->withQueryString()->nextPageUrl()}}">التالي</a>
                                @endif
                                    @if($products->currentPage()>1)
                                <a class="btn btn-sm btn-secondary" href="{{$products->withQueryString()->previousPageUrl()}}">السابق</a>
                                    @endif
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
                        @foreach($categories as $category)
                            <a href="{{route('seller.profile',['id'=>$store->id,'category_id'=>$category->id])}}">
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
