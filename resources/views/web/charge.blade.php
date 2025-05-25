@extends('layouts.master_layouts')
@section('content')
    <div class="container">
        <div class="row justify-content-center" style="margin-top: 100px">
            <div class="col-12">
                <p class="text-bold"><span class="text-red">ملاحظة :</span> قد يستغرق شحن الرصيد في تطبيق علي باشا من 5
                    إلى 30 دقيقة</p>
                <p class="text-bold"><span class="text-red">هام جداً :</span> تأكد من إضافة المعرف الخاص بك في علي باشا
                    إلى ملاحظات الحوالة في تطبيق شام كاش</p>
                <p class="text-bold"><span class="text-red">معرفك هو  :</span> {{auth()->id()}}</p>

            </div>
            <div class="col-md-7">
                <img src="{{$setting->getImage('sham-cash')}}" class="img-fluid" alt="">
                <p class="text-bold"><span class="text-red">رقم الحساب على شام كاش  :</span> {{$setting->wallet}}</p>
            </div>
            <div class="col-md-7">
                <img src="{{asset('images/payment/1.png')}}" class="img-fluid" alt="">
            </div>
            <div class="col-md-7">
                <img src="{{asset('images/payment/2.png')}}" class="img-fluid" alt="">
            </div>
            <div class="col-md-7">
                <img src="{{asset('images/payment/3.png')}}" class="img-fluid" alt="">
            </div>

        </div>
    </div>
@endsection
