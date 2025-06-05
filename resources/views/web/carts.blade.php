@extends('layouts.master_layouts')
@section('title')
    السلة
@endsection
@section('content')
    <div class="container">
        <div class="row justify-content-center" style="margin-top: 100px">
            @forelse($carts as $cart)
                <div class="col-md-8 ">
                    <a href="{{route('carts.show',$cart->seller_id)}}">


                        <div class="card mb-3" style="max-width: 540px;">
                            <div class="row g-0">

                                <div class="col-8">
                                    <div class="card-body" dir="rtl">
                                        <h4 class="card-title text-right">{{$cart->seller?->seller_name}}</h4>
                                        <h6 class="text-muted text-right">{{$cart->seller?->info}}</h6>
                                        <h6 class="text-muted text-right"><i class="bi bi-geo-alt-fill"></i> {{$cart->seller?->address}}</h6>

                                    </div>
                                </div>
                                <div class="col-4">
                                    <img src="{{$cart->seller?->hasMedia('logo')?$cart->seller?->getImage('logo'):$cart->seller?->getImage('image')}}" style="aspect-ratio: 1/1;width:100%"
                                         class="img-fluid rounded-end" alt="...">
                                </div>
                            </div>
                        </div>
                    </a>
                </div>

            @empty
                <div class="col-md-8 ">
                    <h4 class="alert alert-danger">لا يوجد عناصر في السلة</h4>

                </div>

            @endforelse
        </div>
    </div>
@endsection
