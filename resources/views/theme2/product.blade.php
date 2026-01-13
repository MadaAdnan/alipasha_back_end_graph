@extends('theme2.layouts.master')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-9">
                <x-component.slider-component :items="$post->getImages('images')"/>
            </div>
            <div class="col-md-3"></div>
        </div>
    </div>

@endsection
