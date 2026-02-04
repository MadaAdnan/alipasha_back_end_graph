@props([
    'product'=>null,
    'class'=>null
])
<div class="estate-card d-flex border rounded overflow-hidden {{$class}}">

    {{-- السلايدر --}}
    <div id="estateCarousel{{ $product->id }}" class="carousel slide estate-image" data-bs-ride="carousel">

        {{-- الصور --}}
        <div class="carousel-inner">
            @foreach($product->getMedia('images') as $index => $img)
                <div class="carousel-item {{ $index == 0 ? 'active' : '' }}">
                    <a href="{{route('posts.show',  $product->id)}}" >
                    <img src="{{ $img->getUrl('webp') }}" class="d-block slider-img" alt="image">
                    </a>
                </div>
            @endforeach
        </div>

        {{-- الأسهم --}}
        <button class="carousel-control-prev" type="button" data-bs-target="#estateCarousel{{ $product->id }}" data-bs-slide="prev">
            <span class="carousel-control-prev-icon"></span>
        </button>

        <button class="carousel-control-next" type="button" data-bs-target="#estateCarousel{{ $product->id }}" data-bs-slide="next">
            <span class="carousel-control-next-icon"></span>
        </button>

        {{-- النقاط --}}
        <div class="carousel-indicators">
            @foreach($product->getMedia('images') as $index => $img)
                <button type="button"
                        data-bs-target="#estateCarousel{{ $product->id }}"
                        data-bs-slide-to="{{ $index }}"
                        class="{{ $index == 0 ? 'active' : '' }}">
                </button>
            @endforeach
        </div>
    </div>

    {{-- التفاصيل --}}
    <div class="estate-info p-2 flex-fill">
        <a href="{{route('posts.show',  $product->id)}}" >
        <h6 class="estate-title pe-2 ">{{ $product->expert }}</h6>
        </a>

        <div class="estate-price text-success fw-bold ">
            <x-components.price-component  :price="$product->price" :discount="$product->discount" :isDiscount="$product->is_discount" />

        </div>

        <div class="estate-meta d-flex align-items-center gap-3 text-secondary small mb-3">
            <span><i class="fa fa-ruler"></i> {{ $product->city?->name }} م²</span>
            <span><i class="fa fa-seedling"></i> {{ $product->type }}</span>
            <span><i class="fa fa-calendar"></i> {{ $product->created_at?->format('Y-m') }}</span>
            <span><i class="fa fa-map-marker-alt"></i> {{ $product->user?->address }}</span>
        </div>

        <div class="estate-actions d-flex gap-4 align-items-center">
            <a href="tel:{{$product->user?->full_phone}}" class="text-danger text-decoration-none fw-bold" target="_blank">
                <i class="fa fa-phone"></i> الاتصال
            </a>

            <span onclick="clickWhats('{{$product->user?->full_phone}}')" class="text-success fw-bold text-decoration-none">
                <i class="fa-brands fa-whatsapp fs-6"></i>
            </span>
        </div>

    </div>

</div>
<script>
    function clickWhats(full_phone) {
        const auth = "{{auth()->check()}}";
        if (auth == "") {
            localStorage.removeItem('token');
            showToast('يرجى تسجيل الدخول اولاً', 'error')

            return;
        } else if (localStorage.getItem('token') == null) {

            @auth localStorage.setItem('token', '{{auth()->user()->createToken('MyApp')->plainTextToken}}') @endauth
        }
        if(full_phone==''){
            return;
        }

        fetch(`/api/click-whats`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                ...(localStorage.getItem('token') && {
                    'Authorization': 'Bearer ' + localStorage.getItem('token')
                })
            },
            body: JSON.stringify({
                product_id: {{$product->id}}
            })
        })
            .then(response => {

                if (response) {

                    return response.json(); // نستخدم json() لأن الاستجابة الآن تكون ككائن JSON
                }
                throw new Error('Network response was not ok');
            })
            .then(data => {

                window.open(`https://wa.me/${full_phone}`, '_blank');


            })
            .catch(error => {
                console.error('Error:', error);
                showToast(error, 'error');
            });


    }
</script>
