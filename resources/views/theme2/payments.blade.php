@extends('theme2.layouts.master')

@section('content')
    <div class="filter-container">
        <div class="filter-header">
            <i class="fas fa-sliders-h"></i>
            <span>طريقة شحن الحساب</span>
        </div>
        <div class="bg-white">
            <img src="{{asset('images/payment/payment1.jpg')}}" alt="Payment Method 1">
            <img src="{{asset('images/payment/payment2.jpg')}}" alt="Payment Method 2">
            <img src="{{asset('images/payment/payment3.jpg')}}" alt="Payment Method 3">
        </div>
    </div>

@endsection
