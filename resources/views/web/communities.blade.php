@extends('layouts.master_layouts')
@section('content')
    <div class="container-fluid" style="margin-top: 70px">
        <div class="row justify-content-center">

            <div class="col-md-6">
                @foreach($communities as $community)
                <div class="card card-body">
                    <div class="d-flex align-items-center justify-content-between">
                      <div>
                          <img src="{{$community->getImage()}}" style="width: 100px;" alt="" class="rounded-circle mx-2">
                          <span>{{$community->name}}</span>
                      </div>
                        <a href="{{route('communities.show',$community->id)}}" class="btn btn-sm btn-info">إنتقل إلى المحادثة</a>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection
