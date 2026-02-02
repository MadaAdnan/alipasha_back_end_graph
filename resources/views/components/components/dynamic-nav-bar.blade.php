@props([
    'class'=>null
])
<nav class="navbar navbar-expand-lg bg-white  {{$class}}">
    <div class="container ">

        <button class="navbar-toggler d-lg-none" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <!-- Shopping Cart with Badge -->
        <span class="d-flex justify-content-end">
            @auth
                <a href="{{ route('payments.index') }}"  class="cart-link mx-1 position-relative">

            <i class="fa fa-wallet fa-lg"></i>
                                            <span class="badge badge-danger position-absolute top-0 start-100 translate-middle rounded-pill bg-danger">{{auth()->user()->getTotalBalance()}} $</span>


        </a>
            @endauth

            <a href="{{ route('carts.index') }}"  class="cart-link mx-1 position-relative">
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
        <a href="{{ route('notifications.index') }}"  class="cart-link mx-1 position-relative">
            <i class="fa fa-bell fa-lg"></i>
            @if(auth()->check())
                @php
                    $notificationsCount = auth()->user()->unread_notifications_count;
                @endphp
                @if($notificationsCount > 0)
                    <span class="badge badge-danger position-absolute top-0 start-100 translate-middle rounded-pill bg-danger">{{ $cartCount }}</span>
                @endif
            @endif
        </a>
        <a href="{{ route('communities.index') }}"  class="cart-link mx-1 position-relative">
            <i class="fa fa-comments fa-lg"></i>
           {{-- @if(auth()->check())
                @php
                    $notificationsCount = auth()->user();
                @endphp
                @if($notificationsCount > 0)
                    <span class="badge badge-danger position-absolute top-0 start-100 translate-middle rounded-pill bg-danger">{{ $cartCount }}</span>
                @endif
            @endif--}}
        </a>
        </span>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                @foreach($navs as $nav)
                    @php
                        $url=$nav->url;
                        if(!Str::startsWith($nav->url,['https://','http://','//'])){
                            $url=url($nav->url);
                        }

                    @endphp
                    <li class="nav-item nav-divider">
                        <a class="nav-link active text-muted" aria-current="page" href="{{$url}}"> <i class="{{$nav->icon}}"></i><span class="d-inline-block mx-1">{{$nav->title}}</span></a>
                    </li>
                @endforeach

                @auth()
                    <!-- Divider -->
                    <li class="nav-item">
                        <hr class="dropdown-divider my-2">
                    </li>

                    <!-- Logout Button - Mobile Only -->
                    <li class="nav-item d-lg-none">
                        <form action="{{route('logout')}}" method="post" class="w-100">
                            @csrf
                            @method('POST')
                            <button type="submit" class="nav-link text-danger w-100 text-start">
                                <i class="fa-solid fa-arrow-right-from-bracket"></i>
                                <span class="d-inline-block mx-1">تسجيل الخروج</span>
                            </button>
                        </form>
                    </li>
                @endauth
            </ul>
        </div>
    </div>
</nav>
