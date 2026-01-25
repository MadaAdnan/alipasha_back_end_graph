@props([
    'post'=>null,
    'class'=>''
])
<div class="like-btn-component d-inline-block {{$class}}">
    @php
$isLike=auth()->check() && auth()->user()?auth()->user()->likes->pluck('product_id')->contains($post->id):false;
 @endphp
    <form @if(auth()->check()) action="{{route('post.like')}}" @endif method="post">
        @csrf
        @method('POST')
        <input type="hidden" name="productId" value="{{$post->id}}">
        <button  class="btn like-btn text-center @if($isLike) active  @endif " style="padding-block: 3px;
  padding-inline: 6px;">
            <i class="fa fa-heart"></i>
        </button>
    </form>
</div>
