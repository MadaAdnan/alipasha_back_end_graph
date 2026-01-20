@props([
    'post'=>null,
    'class'=>''
])
<div class="like-btn-component d-inline-block {{$class}}">
    <form @if(auth()->check()) action="{{route('carts.store')}}" @endif method="post">
        @csrf
        @method('POST')
        <input type="hidden" name="productId" value="{{$post->id}}">
        <button type="button" class="btn like-btn text-center @if(auth()->user()) active  @endif ">
            <i class="fa-solid fa-cart-plus"></i>
        </button>
    </form>
</div>
