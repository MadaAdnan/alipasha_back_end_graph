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
                    <div class="bg-white d-flex flex-column gap-2 align-items-center justify-content-center">
                        @php
$wallet=\App\Models\Setting::first()?->wallet;
 @endphp
                        <p class="lead "><span>المحفظة:</span> <span  class="d-inline-block border border-1 p-2 fw-bolder">{{$wallet}}</span><i class="fa fa-copy" onclick="copyTextToClipboard('{{$wallet}}')"></i></p>
                        <p class="lead"><span>معرفك الشخصي:</span> <span class="d-inline-block border border-1 p-2 fw-bolder">{{auth()->id()}}</span><i class="fa fa-copy" onclick="copyTextToClipboard('{{auth()->id()}}')"></i></p>
                        <img class="payment-img" src="{{asset('images/payment/payment1.jpg')}}" alt="Payment Method 1">
                        <img class="payment-img" src="{{asset('images/payment/payment2.jpg')}}" alt="Payment Method 2">
                        <img class="payment-img" src="{{asset('images/payment/payment3.jpg')}}" alt="Payment Method 3">
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
