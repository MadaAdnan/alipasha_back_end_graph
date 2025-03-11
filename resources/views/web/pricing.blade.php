@extends('layouts.master_layouts')
@section('style')
    <style>
        body {
            background-color: #fff;
        }
        /* Custom arrow styles */
        .carousel-control-prev,
        .carousel-control-next {
            position: absolute;
            z-index: 9999999999999999;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: transparent;
            border: 1px solid #e30613 !important;
            transition: all 0.3s ease;
        }

        .carousel-control-next {
            top: 50%;
            right: -60px;
            background-color: #e30613;
        }

        .carousel-control-prev {
            top: 50%;
            left: -60px;
            background-color: #e30613;
        }

        @media (max-width: 768px) {
            .carousel-control-next {
                top: 50%;
                right: -10px;
                background-color: #e30613;
            }

            .carousel-control-prev {
                top: 50%;
                left: -10px;
                background-color: #e30613;
            }
        }

        .carousel-control-prev:hover,
        .carousel-control-next:hover {
            background-color: red;
            color: #fff;
        }
    </style>
@endsection
@section('content')
    <div class="container-fluid" style="margin-top: 70px">
        <div class="row">
            <div class="col-12 col-xl-12" style="margin-top: 10px">
                <div class="container mt-5" style="position: relative;">
                    <h1 class="text-center title mb-4">الأسعار والخطط</h1>
                    <p class="text-center sub-title mb-4"> أسعار مناسبة ونتائج تسويقية رائعة</p>
                    <div id="pricingCarousel" class="carousel slide">
                        <div class="carousel-inner">
                            @php
                                $palns=[];
if(auth()->check()){
    $userPlans=auth()->user()->plans->pluck('id')->toArray();

}
                            @endphp
                            @foreach($plans as $plan)
                                <div class="carousel-item @if($loop->iteration==1) active @endif">
                                    <div class="row justify-content-center">
                                        @foreach($plan as $item)
                                            <div class="col-md-4">
                                                <div class="pricing-item  @if(in_array($item->id,$userPlans) ) bg-success-subtle @endif">

                                                    <h4>{{$item->name}}</h4>


                                                    <p style="font-size: 32px; font-weight: 6000; margin-bottom: 8px;">
                                                        @if($item->is_discount)
                                                            <del> {{$item->price}} $</del>
                                                            {{$item->discount}}

                                                        @else
                                                            {{$item->price}} $
                                                        @endif
                                                        <sub
                                                            style="color: #65676b; font-size: 14px;">
                                                            @switch($item->duration)
                                                                @case('free')

                                                                مجانية
                                                                @break

                                                                @case('month')
                                                                في الشهر

                                                                @break

                                                                @case('year')
                                                                في السنة

                                                                @break

                                                            @endswitch
                                                        </sub>
                                                    </p>
                                                    {{--<p style="font-size: 16px; color: #000000;margin: 20px 0px;">Perfect for individuals starting out.</p>--}}

                                                    <form action="">
                                                        <input type="hidden" name="free" value="123">
                                                        <button @if(!in_array($item->id,$userPlans) ) type="submit"  style="width: 100%; background-color: #000000; color: #fff; margin: 20px 0px;"  @else type="button"  style="width: 100%; background-color: transparent; border: #00bb00 solid 2px; color: #000; margin: 20px 0px;"   @endif class="btn"
                                                               >
                                                            @if(!in_array($item->id,$userPlans) ) اشترك بالخطة  @else تم الاشتراك   @endif
                                                        </button>
                                                    </form>

                                                    <div class="btn"
                                                         style="width: 100%; margin: 0px auto 30px auto; border-bottom: 2px dashed #ccc;"></div>


                                                    <ul style="margin-top: 40px;">
                                                        @if($item->type==\App\Enums\PlansTypeEnum::PRESENT->value)
                                                            <li><i class="bi bi-check-circle"></i> عدد المنتجات في الشهر {{$item->products_count}}</li>
                                                            <li><i class="bi bi-check-circle"></i> عدد الإعلانات في الشهر {{$item->ads_count}}</li>
                                                            <li><i class="bi bi-check-circle"></i> عدد المنتجات المميزة {{$item->special_count}}</li>
                                                        @endif
@foreach($item->items as $option)
                                                                <li>@if($option['active']) <i class="bi bi-check-circle"></i> @else  <i class="bi bi-cancel-circle"></i> @endif {{$option['item']}}</li>
@endforeach


                                                    </ul>
                                                </div>
                                            </div>
                                        @endforeach


                                    </div>
                                </div>
                            @endforeach


                        </div>
                        <!-- Carousel Controls -->
                        <button class="carousel-control-prev" type="button" data-bs-target="#pricingCarousel"
                                data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#pricingCarousel"
                                data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Next</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
    </div>
@endsection
