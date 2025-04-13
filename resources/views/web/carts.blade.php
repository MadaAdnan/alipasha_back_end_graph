@extends('layouts.master_layouts')
@section('content')
    <div class="container">
        <div class="row justify-content-center" style="margin-top: 100px">
            @forelse($carts as $cart)
                <div class="col-md-8 ">
                    <div class="card mb-3" style="max-width: 540px;">
                        <div class="row g-0">

                            <div class="col-8">
                                <div class="card-body">
                                    <h5 class="card-title">{{$cart->seller?->seller_name}}</h5>
                                </div>
                            </div>
                            <div class="col-4">
                                <img src="{{$cart->seller?->getImage('logo')}}" style="aspect-ratio: 1/1;width:100%" class="img-fluid rounded-end" alt="...">
                            </div>
                        </div>
                    </div>

                </div>
            @empty
                <div class="col-md-8 ">
                    <h4 class="alert alert-danger">لا يوجد عناصر في السلة</h4>

                </div>

            @endforelse
        </div>
    </div>
@endsection
