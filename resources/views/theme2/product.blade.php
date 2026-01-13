@extends('theme2.layouts.master')

@section('content')
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-9">
                <x-component.slider-component :items="$post->getImages('images')"/>

            </div>
            <div class="col-md-3">
                <x-component.seller-info-component :seller="$post->user"/>
            </div>
        </div>
    </div>

@endsection
