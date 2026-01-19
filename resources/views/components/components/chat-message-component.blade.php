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
    </div>
@else
    <div class="message d-flex gap-1">
        <img src="{{$user->getImage()}}" alt="{{$user->name}}">
    </div>
@endif
