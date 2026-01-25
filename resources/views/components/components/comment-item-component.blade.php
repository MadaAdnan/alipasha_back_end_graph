@props([
    'comment'=>null
])
@php
$user=$comment->user;
 @endphp
<div class="comment d-flex">
    <img src="{{$user?->getImage()}}"
         class="avatar"
         alt="{{$user->name}}"
         onerror="this.src='{{ asset('images/user-profile.png') }}'; this.classList.add('avatar-no-image');">

    <div class="comment-body">
        <div class="comment-box">
            <strong>{{$user->name}}</strong>
            <p>{{$comment->comment}}</p>
        </div>

        <div class="comment-actions">

           {{-- <a href="#">رد</a> ·--}}
            <span>{{$comment->created_at?->diffForHumans()}}</span>
        </div>
@foreach($comment->comments  as $replay)
            <!-- الردود -->
            <x-components.commen-replay-component :replay="$replay"/>
@endforeach


    </div>
</div>
