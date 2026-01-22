@props([
    'replay'=>null
])
@php
$user=$replay->user;
 @endphp
<div class="replies">
    <div class="comment d-flex">
        <img src="{{$user->getImage()}}" class="avatar small">

        <div class="comment-body">
            <div class="comment-box">
                <strong>{{$user->name}}</strong>
                <p>{{$replay->comment}}</p>
            </div>

            <div class="comment-actions">
               {{-- <a href="#">إعجاب</a> ·
                <a href="#">رد</a> ·--}}
                <span>{{$replay->created_at?->diffForHumans()}}</span>
            </div>
        </div>
    </div>
</div>
