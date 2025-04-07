@extends('layouts.master_layouts')
@section('content')
    <div class="container-fluid" style="margin-top: 70px">
        <div class="row justify-content-center">

            <div class="col-md-6">
                @foreach($communities as $community)
                <div class="card card-body">
                    <div class="d-flex align-items-center">
                        <img src="{{$community->getImage()}}" style="width: 100px;" alt="" class="rounded-circle">
                        <span>{{$community->name}}</span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection
