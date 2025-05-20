@extends('layouts.master_layouts')
@section('style')
    <style>
        .chat-panel{
            height:70vh;
            overflow-y: scroll;
        }
        .chat-panel-write{


            background:
                #d3cccc;
            z-index: 40;
            padding-block:
                30px;
            height: 20vh;

        }
    </style>
@endsection
@section('content')
    <div class="container-fluid" style="margin-top: 70px">
        <div class="row justify-content-center">

            <div class="col-md-6 ">
                <div class="card position-relative pb-1 px-2 chat-panel">
                    <div class="d-flex flex-column">
                        <div class="chat-board">
                            @foreach($messages as $message)
                                @php
                                    $user=$message->user;
                                @endphp
                                <div
                                    class="d-flex flex-column @if($user->id==auth()->id()) align-items-start  @else align-items-end  @endif justify-content-center">
                                    <div
                                        class="d-flex flex-row justify-content-center align-items-center @if($user->id!=auth()->id()) flex-row-reverse    @endif">
                                        <img style="width: 50px;height: 50px" src="{{$user?->getImage()}}" alt=""
                                             class="rounded-circle">
                                        <span>{{$user?->seller_name ??$user?->name}}</span>
                                    </div>
                                    <div
                                        class="card card-body my-1 w-75 @if($user->id==auth()->id()) bg-success-subtle   @endif">
                                        <div class="d-flex flex-column gap-2">
                                            @php
                                                $body=explode(' ',$message->body);
                                            $messageBody='';
                                            foreach ($body as $b){
                                                if(filter_var(trim($b,'.'),FILTER_SANITIZE_URL) ){
                                                    $messageBody.=" <a href='{$b}'>{$b}</a> ";
                                                }else{
                                                    $messageBody.=" {$b}";
                                                }
                                            }
                                            @endphp
                                            <span>{!! $messageBody!!}</span>
                                            @if($message->type=='webp')
                                                <img src="{{$message->getImage('attach')}}" style="width: 70%;height: fit-content" alt="">
                                            @endif
                                            @if($message->type=='aac')
                                                <audio src="{{$message->getImage('attach')}}" controls/>
                                            @endif
                                            <span class="text-muted small">{{$message->created_at?->diffForHumans()}}</span>
                                        </div>
                                    </div>
                                </div>

                            @endforeach
                        </div>


                    </div>


                </div>
                @if($community->type==\App\Enums\CommunityTypeEnum::CHAT->value || $community->type==\App\Enums\CommunityTypeEnum::GROUP->value)
                    <div class=" chat-panel-write w-100 d-flex align-items-center  overflow-hidden">
                        <form action="{{route('messages.store')}}" method="post" class="d-flex w-100 gap-1 px-3 py-1 ">
                            @csrf
                            @method('post')
                            <button class="btn btn-sm btn-outline-danger " type="submit"><i class="bi bi-send-fill fs-4"></i>
                            </button>
                            <div class="bg-secondary-subtle w-100 p-1 rounded">
                                <input type="text" class="form-control " name="body" placeholder="اكتب شيئاً ...">
                            </div>
                            <input type="hidden" name="communityId" value="{{$community->id}}">

                        </form>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
