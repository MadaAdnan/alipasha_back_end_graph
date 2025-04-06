@extends('layouts.master_layouts')
@section('content')
    <div class="container-fluid" style="margin-top: 70px">
        <div class="row justify-content-center">

            <div class="col-md-8">
                @foreach($messages as $message)
                    @php
                        $user=$message->user;
                    @endphp
                    <div class="d-flex flex-column @if($user->id==auth()->id()) align-items-start  @else align-items-end  @endif justify-content-center">
                       <div class="d-flex flex-row justify-content-center">
                           <img style="width: 50px;height: 50px" src="{{$user?->getImage()}}" alt="" class="rounded-circle">
                           <span>{{$user?->seller_name ??$user?->name}}</span>
                       </div>
                        <div class="card card-body my-1  @if($user->id==auth()->id()) bg-success-subtle   @endif">
                            <div class="d-flex">

                                <span>{{$message->body}}</span>
                            </div>
                        </div>
                    </div>

                @endforeach
            </div>
        </div>
    </div>
@endsection
