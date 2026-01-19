@props([
    'message'=>null
])
@php
    $user=$message->user;
    $type=$message->type;
@endphp

@if($user->id==auth()->id())
    <div class="d-flex flex-column gap-1">
    <div class="message d-flex flex-row-reverse gap-1">
        <img src="{{$user->getImage()}}" alt="{{$user->name}}">
        <p class="bg-white rounded text-wrap p-2">{{$message->body}}</p>

    </div>
        <span>{{$message->created_at->diffForHumans()}}</span>
    </div>
@else
    <div class="d-flex flex-column gap-1">
        <div class="message d-flex  gap-1">
            <img src="{{$user->getImage()}}" alt="{{$user->name}}">
            <p class="bg-white rounded text-wrap p-2">{{$message->body}}</p>

        </div>
        <span>{{$message->created_at->diffForHumans()}}</span>
    </div>
@endif
