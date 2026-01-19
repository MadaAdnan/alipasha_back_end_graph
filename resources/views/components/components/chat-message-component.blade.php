@props([
    'message'=>null
])
@php
    $user=$message->user;
    $type=$message->type;
@endphp

@if($user->id==auth()->id())
    <div class="message d-flex gap-1">
        <img src="{{$user->getImage()}}" alt="{{$user->name}}">
        <p class="bg-white rounded text-wrap">{{$message->body}}</p>
        <span>{{$message->created_at->diffForHumans()}}</span>
    </div>
@else
    <div class="message d-flex gap-1">

        <p class="bg-white rounded text-wrap">{{$message->body}}</p>
        <span>{{$message->created_at->diffForHumans()}}</span>
        <img src="{{$user->getImage()}}" alt="{{$user->name}}">
    </div>
@endif
