@extends('theme2.layouts.master')

@section('content')
    <div class="container my-4">
        <div class="service-details card">
            <div class="card-body">

                <h4 class="fw-bold mb-4">
                    خدمة : <span class="text-primary">قسم الخدمات الرئيسية</span>
                </h4>

                <div class="row g-4">

                    <!-- التفاصيل -->
                    <div class="col-md-7">
                        <h5 class="fw-bold mb-3">كهرباء منزلية وصناعية</h5>

                        <ul class="service-list">
                            <li><i class="fa-solid fa-bolt"></i> كهرباء عدادات وأمبيرات</li>
                            <li><i class="fa-solid fa-house"></i> تمديد كهرباء منزلي</li>
                            <li><i class="fa-solid fa-industry"></i> تمديد وصيانة معامل صناعية</li>
                            <li><i class="fa-solid fa-solar-panel"></i> تركيب جميع أنواع الطاقة الشمسية</li>
                            <li><i class="fa-solid fa-fire"></i> تمديد أنظمة تدفئة مركزية</li>
                            <li><i class="fa-solid fa-wrench"></i> فني ديكورات كهرباء وبدائل</li>
                        </ul>

                        <div class="service-info">
                            <p><i class="fa-solid fa-phone"></i> 0949291242</p>
                            <p><i class="fa-brands fa-whatsapp"></i> 00905340327364</p>
                            <p><i class="fa-solid fa-location-dot"></i> حلب – إعزاز</p>
                            <p class="text-muted small">منذ 3 أسابيع</p>
                        </div>
                    </div>

                    <!-- الصورة -->
                    <div class="col-md-5">
                        <div class="service-image">
                            <img src="https://via.placeholder.com/400x500"
                                 class="img-fluid rounded"
                                 alt="service">
                        </div>
                    </div>

                </div>

                <!-- زر الإبلاغ -->
                <div class="mt-4">
                    <button class="btn btn-danger w-100">
                        <i class="fa-solid fa-flag"></i> إبلاغ عن الخدمة
                    </button>
                </div>

            </div>
        </div>
    </div>

@endsection
@push('css')
    <style>


        .service-details {
            border-radius: 12px;
        }

        .service-list {
            list-style: none;
            padding: 0;
        }

        .service-list li {
            margin-bottom: 8px;
            font-size: 15px;
        }

        .service-list i {
            color: #0d6efd;
            margin-left: 6px;
        }

        .service-info p {
            margin-bottom: 5px;
            font-size: 14px;
        }

        .service-info i {
            color: #198754;
            margin-left: 6px;
        }

        .service-image img {
            box-shadow: 0 8px 20px rgba(0,0,0,.15);
        }

    </style>

@endpush

