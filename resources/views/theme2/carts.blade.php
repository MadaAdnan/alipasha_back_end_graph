@extends('theme2.layouts.master')

@section('content')
    <div class="container mt-4">
        <div class="row">
            <div class="col-md-3">

                <x-components.seller-cart-component :carts="$carts"/>
            </div>
            <div class="col-md-9">
                <x-components.cart-container-component :seller="$seller"/>
            </div>
        </div>
    </div>
@endsection
