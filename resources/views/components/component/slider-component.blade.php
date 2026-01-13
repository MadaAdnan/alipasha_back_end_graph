@props([
    'id'=>'main-slider',
    'items'=>[],
])
<div id="carousel-{{ $id ?? uniqid() }}" class="carousel slide slider-component" data-bs-ride="carousel">
    <div class="carousel-inner">
        @foreach($items as $item)
            <div class="carousel-item @if($loop->first) active @endif">
                <img src="{{ $item['image'] }}" class="d-block w-100 carousel-img" alt="...">
            </div>
        @endforeach
    </div>

    <button class="carousel-control-prev" type="button" data-bs-target="#carousel-{{ $id ?? uniqid() }}" data-bs-slide="prev">
        <span class="carousel-control-prev-icon"></span>
    </button>

    <button class="carousel-control-next" type="button" data-bs-target="#carousel-{{ $id ?? uniqid() }}" data-bs-slide="next">
        <span class="carousel-control-next-icon"></span>
    </button>
</div>
