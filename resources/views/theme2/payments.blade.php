@extends('theme2.layouts.master')

@section('content')
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-9">
                <div class="filter-container">
                    <div class="filter-header">
                        <i class="fas fa-sliders-h"></i>
                        <span>طريقة شحن الحساب</span>
                    </div>
                    <div class="bg-white d-flex flex-column gap-2 align-items-start justify-content-start">
                        @php
                            $wallet=\App\Models\Setting::first()?->wallet;
                        @endphp
                       <div class="">
                           <p class="lead "><p>المحفظة:</p> <p  class="d-inline-block border border-1 p-2 fw-bolder rounded">{{$wallet}}</p><i class="fa fa-copy" onclick="copyTextToClipboard('{{$wallet}}')"></i></p>
                           <p class="lead"><p>معرفك الشخصي:</p> <p class="d-inline-block border border-1 p-2 fw-bolder rounded">{{auth()->id()}}</p><i class="fa fa-copy" onclick="copyTextToClipboard('{{auth()->id()}}')"></i></p>

                       </div>
                        <div class=" d-flex flex-column gap-3 align-items-center justify-content-center w-100">

                            <img class="payment-img" src="{{asset('images/payment/payment1.jpg')}}" alt="Payment Method 1">
                            <div class="divider border-top border-danger"></div>
                            <img class="payment-img" src="{{asset('images/payment/payment2.jpg')}}" alt="Payment Method 2">
                            <div class="divider border-top border-danger"></div>
                            <img class="payment-img" src="{{asset('images/payment/payment3.jpg')}}" alt="Payment Method 3">
                        </div>

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
