<nav class="navbar navbar-expand bg-white">
    <div class="container">
        <a class="navbar-brand" href="{{route('index')}}">
            <img class="logo" src="{{$setting?->getFirstMediaUrl('logo')}}" alt=""></a>
        <!-- Shopping Cart with Badge -->
        <a href="{{ route('carts.index') }}" style="margin-inline: 15px" class="cart-link  position-relative">
            <i class="fa fa-shopping-cart fa-lg"></i>
            @if(auth()->check())
                @php
                    $cartCount = \App\Models\Cart::where('user_id', auth()->id())->count();
                @endphp
                @if($cartCount > 0)
                    <span class="badge badge-danger position-absolute top-0 start-100 translate-middle rounded-pill bg-danger">{{ $cartCount }}</span>
                @endif
            @endif
        </a>

        {{--<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>--}}
        <div class="collapse navbar-collapse" id="navbarSupportedContent1">

            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                {{--<li>
                    <form class="d-flex position-relative" role="search">
                        <input class="form-control me-2" type="search" placeholder="بحث" aria-label="Search"/>

                        <button class="transparent position-absolute search-icon-navbar"><i class="fa fa-search"></i></button>
                    </form>
                </li>--}}
@guest()
                <li class="nav-item">
                    <a class="nav-link" href="{{route('login')}}"><i class="fa fa-user"></i> تسجيل الدخول </a>
                </li>

                @endguest
                @auth()
                <li class="nav-item">
                    <a class="nav-link d-none d-md-inline-block" href="{{route('seller.profile',auth()->id())}}"><i class="fa fa-user"></i> {{auth()->user()->name}} </a>
                    <a class="nav-link d-inline-block d-md-none" href="{{route('seller.profile',auth()->id())}}"><i class="fa fa-user"></i> {{Str::limit(auth()->user()->name,1)}} </a>
                </li>
                <li class="nav-item">
                    <form action="{{route('logout')}}" method="post">
                        @csrf
                        @method('POST')
                        <button class="btn btn-danger d-none d-md-inline-block">تسجيل الخروج</button>
                        <button class="btn btn-danger d-inline-block d-md-none"><i class="fa-solid text-white fa-arrow-right-from-bracket"></i></button>
                    </form>
                </li>
                @endauth
            </ul>

        </div>
    </div>
</nav>
