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
                    <div class="bg-white d-flex flex-column gap-2 align-items-start justify-content-center">
                        <img class="payment-img" src="{{asset('images/payment/payment1.jpg')}}" alt="Payment Method 1">
                        <img class="payment-img" src="{{asset('images/payment/payment2.jpg')}}" alt="Payment Method 2">
                        <img class="payment-img" src="{{asset('images/payment/payment3.jpg')}}" alt="Payment Method 3">
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
