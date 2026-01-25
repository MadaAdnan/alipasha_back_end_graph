@props([
    'message'=>null
])
@php
    $user=$message->user;
    $type=$message->type;
    $isCurrentUser = $user->id == auth()->id();
@endphp

<div class="message-wrapper {{ $isCurrentUser ? 'message-sent' : 'message-received' }}" data-message-id="{{ $message->id }}">
    <div class="message-content">
        @if(!$isCurrentUser)
            <img src="{{$user->getImage()}}" alt="{{$user->name}}" class="message-avatar" title="{{$user->name}}">
        @endif

        <div class="message-bubble-wrapper">
            @if(!$isCurrentUser)
                <span class="message-sender-name">{{$user->name}}</span>
            @endif
            <div class="message-bubble {{ $isCurrentUser ? 'message-bubble-sent' : 'message-bubble-received' }}">
                <p class="message-text">{{$message->body}}</p>
                <span class="message-time" title="{{ $message->created_at->format('Y-m-d H:i') }}">{{$message->created_at->format('H:i')}}</span>
            </div>
        </div>

        @if($isCurrentUser)
            <img src="{{$user->getImage()}}" alt="{{$user->name}}" class="message-avatar" title="{{$user->name}}">
        @endif
    </div>
</div>
