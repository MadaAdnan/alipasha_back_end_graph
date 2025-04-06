@extends('layouts.master_layouts')
@section('content')
    <div class="container-fluid" style="margin-top: 70px">
        <div class="row justify-content-center">

            <div class="col-md-6 ">
                <div class="card position-relative pb-5">
                @foreach($messages as $message)
                    @php
                        $user=$message->user;
                    @endphp
                    <div class="d-flex flex-column @if($user->id==auth()->id()) align-items-start  @else align-items-end  @endif justify-content-center">
                       <div class="d-flex flex-row justify-content-center align-items-center @if($user->id!=auth()->id()) flex-row-reverse    @endif">
                           <img style="width: 50px;height: 50px" src="{{$user?->getImage()}}" alt="" class="rounded-circle">
                           <span>{{$user?->seller_name ??$user?->name}}</span>
                       </div>
                        <div class="card card-body my-1 w-75 @if($user->id==auth()->id()) bg-success-subtle   @endif">
                            <div class="d-flex flex-column gap-2">
@php
    $body=explode(' ',$message->body);
$messageBody='';
foreach ($body as $b){
    if(filter_var($b,FILTER_SANITIZE_URL)){
        $messageBody.=" <a href='{$b}'>{$b}</a> ";
    }else{
        $messageBody.=" {$b}";
    }
}
@endphp
                                <span>{!! $messageBody!!}</span>
                                <span class="text-muted small">{{$message->created_at?->diffForHumans()}}</span>
                            </div>
                        </div>
                    </div>

                @endforeach
                    <div class="position-absolute bottom-0 w-100">
                        <form action="" class="d-flex gap-1">
                            <input type="text" class="form-control">
                            <button class="btn btn-sm btn-outline-danger" type="submit"><i class="bi bi-send-fill"></i></button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
