@extends('theme2.layouts.master')

@section('content')
    <div class="container my-5">
        <div class="service-view card p-4">
            <div class="row align-items-center g-4">

                <!-- النص -->
                <div class="col-lg-7">

                    <x-components.bread-crumb-component  :bread-crumbs="[
                        ['title' => 'الرئيسية', 'url' => route('index')],
                        ['title' => 'الخدمات', 'url' => route('services.index')],
                        ['title' => $service->category?->name, 'url' => route('services.index', ['category_id'=>$service->category?->id])],
                        ['title' => $service->name, 'url' => null],
                    ]"/>


                    <h3 class="fw-bold mb-3">
                        {{$servic->name}}
                    </h3>

                    <p class="service-description">
                        {!! $service->info !!}
                    </p>

                    <div class="service-meta mt-4">
                        <div>
                            <i class="fa-solid fa-location-dot"></i>
                            <span>{{$service->user?->city?->name}} - {{$service->user?->area?->name}}</span>
                        </div>
                        <div>
                            <i class="fa-solid fa-clock"></i>
                            <span>{{$service->created_at?->diffForHumans()}}</span>
                        </div>
                    </div>

                    <div class="contact-box mt-4">
                        <a href="tel:{{$service->phone}}" class="contact-item">
                            <i class="fa-solid fa-phone"></i>
                            {{$service->phone}}
                        </a>

                        <a href="https://wa.me/{{$service->user?->full_phone}}" class="contact-item whatsapp">
                            <i class="fa-brands fa-whatsapp"></i>
                            {{$service->user?->full_phone}}
                        </a>
                    </div>

                    <button class="btn btn-outline-danger mt-4 w-100">
                        <i class="fa-solid fa-flag"></i> إبلاغ عن الخدمة
                    </button>

                </div>

                <!-- الصورة -->
                <div class="col-lg-5">
                    <div class="image-wrapper">
                        <img src="{{$service->getImage()}}"
                             class="img-fluid"
                             alt="service">
                    </div>
                </div>

            </div>
        </div>
    </div>


@endsection
@push('css')
    <style>


        .service-view {
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0,0,0,.08);
        }

        .service-description {
            font-size: 16px;
            line-height: 1.9;
            color: #555;
        }

        .service-meta {
            display: flex;
            gap: 25px;
            font-size: 14px;
            color: #666;
        }

        .service-meta i {
            color: #0d6efd;
            margin-left: 6px;
        }

        .contact-box {
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
        }

        .contact-item {
            background: #f8f9fa;
            padding: 10px 16px;
            border-radius: 25px;
            text-decoration: none;
            color: #333;
            font-size: 14px;
            transition: .2s;
        }

        .contact-item i {
            margin-left: 6px;
        }

        .contact-item:hover {
            background: #e9ecef;
        }

        .contact-item.whatsapp {
            color: #25D366;
        }

        .image-wrapper img {
            border-radius: 14px;
            box-shadow: 0 12px 30px rgba(0,0,0,.2);
        }

    </style>

@endpush

