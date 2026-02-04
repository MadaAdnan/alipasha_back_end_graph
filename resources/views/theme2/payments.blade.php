@extends('theme2.layouts.master')

@section('content')
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-9">
                <div class="filter-container">
                    <div class="filter-header">
                        <i class="fas fa-wallet"></i>
                        <span>طريقة شحن الحساب</span>
                    </div>
                    <div class="bg-white d-flex flex-column gap-2 align-items-start justify-content-start">
                        @php
                            $wallet=\App\Models\Setting::first()?->wallet;
                        @endphp

                        <div class=" d-flex flex-column gap-3 align-items-center justify-content-center w-100">

                            <img class="payment-img" src="{{asset('images/payment/payment1.jpg')}}"
                                 alt="Payment Method 1">
                            <p class="lead ">
                            <p>رقم المحفظة الخاص بتطبيق علي باشا يجب نسخه ووضعه في المكان المناسب في تطبيق شام كاش</p>
                            <p class="d-inline-block border border-1 p-2 fw-bolder rounded">{{$wallet}} <span
                                    onclick="copyTextToClipboard('{{$wallet}}')"><i class="fa fa-copy"></i> انسخ </span>
                            </p></p>

                            <div class="divider border-top border-danger w-75 m-auto"></div>
                            <img class="payment-img" src="{{asset('images/payment/payment2.jpg')}}"
                                 alt="Payment Method 2">

                            <div class="divider border-top border-danger w-75 m-auto"></div>
                            <img class="payment-img" src="{{asset('images/payment/payment3.jpg')}}"
                                 alt="Payment Method 3">
                            <p class="lead">
                            <p>يجب وضع رقم المعرف الخاص بك في الملاحظات في تطبيق شام كاش</p>
                            <p class="d-inline-block border border-1 p-2 fw-bolder rounded">{{auth()->id()}} <span
                                    onclick="copyTextToClipboard('{{auth()->id()}}')"> <i
                                        class="fa fa-copy"></i> انسخ</span></p></p>

                        </div>
                        <p class=""><span class="text-danger lead">ملاحظة :</span> <span class="lead text-black">قم بالتحويل بالدولار وفي حال تم التحويل بعملة أخرى سيتم إحتساب قيمتها بالدولار وشحن حسابك بالدولار</span>
                        </p>

                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('js')
    <script>
        function copyTextToClipboard(text) {
            navigator.clipboard.writeText(text);
            showToast('تم نسخ النص بنجاح');
        }
    </script>

@endpush
