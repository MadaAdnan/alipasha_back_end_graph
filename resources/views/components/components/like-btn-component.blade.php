@props([
    'post'=>null,
    'class'=>''
])
<div class="like-btn-component d-inline-block {{$class}}">
    <form @if(auth()->check()) action="{{route('post.like')}}" @endif method="post">
        @csrf
        @method('POST')
        <input type="hidden" name="productId" value="{{$post->id}}">
        <button type="button" class="btn like-btn text-center @if(auth()->user()) active  @endif " style="padding-block: 3px;
  padding-inline: 6px;">
            <i class="fa fa-heart"></i>
        </button>
    </form>
</div>
