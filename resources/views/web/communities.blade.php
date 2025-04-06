@extends('layouts.master_layouts')
@section('content')
    <div class="container-fluid" style="margin-top: 70px">
        <div class="row justify-content-center">

            <div class="col-md-8">
                @foreach($communities as $community)
                <div class="card card-body">
                    <div class="d-flex">
                        <img src="{{$community->getImage()}}" alt="" class="rounded-circle">
                        <span>{{$community->name}}</span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection
