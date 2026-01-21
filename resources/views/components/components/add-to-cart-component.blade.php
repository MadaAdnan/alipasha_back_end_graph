@props([
    'post'=>null,
    'class'=>''
])
<div class="like-btn-component d-inline-block {{$class}}">
    <form  action="{{route('carts.store')}}" method="post">
        @csrf
        @method('POST')
        <input type="hidden" name="productId" value="{{$post->id}}">
        <button type="submit" class="btn cart-btn text-center @if(auth()->user()) active  @endif " style="padding-block: 3px;
  padding-inline: 6px;">
            <i class="fa-solid fa-cart-plus"></i>
        </button>
    </form>
</div>
