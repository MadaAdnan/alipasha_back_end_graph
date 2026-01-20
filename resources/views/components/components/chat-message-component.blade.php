@props([
    'message'=>null
])
@php
    $user=$message->user;
    $type=$message->type;
@endphp

@if($user->id==auth()->id())
    <div class="d-flex flex-column gap-1  justify-content-start align-items-end ">
    <div class="message d-flex  gap-1 ">
        <img src="{{$user->getImage()}}" alt="{{$user->name}}">
        <p class="bg-me rounded text-wrap p-2">{{$message->body}}</p>

    </div>
        <span>{{$message->created_at->diffForHumans()}}</span>
    </div>
@else
    <div class="d-flex flex-column gap-1 justify-content-start align-items-start ">
        <div class="message d-flex  gap-1 ">
            <img src="{{$user->getImage()}}" alt="{{$user->name}}">
            <p class="bg-another rounded text-wrap p-2">{{$message->body}}</p>

        </div>
        <span>{{$message->created_at->format('Y-m-d | H:i')}}</span>
    </div>
@endif
