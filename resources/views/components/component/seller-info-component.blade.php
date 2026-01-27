@props([
    'seller'=>null,
    'productId'=>null,
    'post'=>null,
])

<div class="card card-body seller-data">
    <div class="d-flex justify-content-between align-items-center">
        <h6 class="text-gray fw-bold my-1">معلومات المعلن @if($seller->id==auth()->id()) <a href="{{route('profile.index')}}"><i class="fa fa-edit"></i></a> @endif</h6>
        @auth
            @php
                $following=auth()->user()->followers()->pluck('seller_id')->toArray();
                $isFollowing=$following==null?false:in_array($seller->id,$following);
            @endphp
        <form @if(!$isFollowing) action="{{route('following-to-seller',$seller->id)}}" @else action="{{route('unfollowing-to-seller',$seller->id)}}"  @endif method="POST">
            @csrf

            <button class="btn-follow rounded @if($isFollowing) active @endif ">
                <i class="fa fa-bell"></i>
                <span class="small d-none d-md-inline-block mx-1 ">
                    @if($isFollowing) تتابعه
                    @else
                        متابعة
                    @endif


                </span>
            </button>
        </form>
        @else
            <button class="btn-follow rounded" onclick="showToast('يرجى تسجيل الدخول اولاً','error')">
                <i class="fa fa-bell"></i>
                <span class="small d-none d-md-inline-block mx-1 ">

                        متابعة



                </span>
            </button>
            @endauth
    </div>
    <x-components.seller-name-component :seller="$seller" :image="$seller->getImage()"/>
    <span class="small text-muted"><i class="fa fa-location-dot"></i> {{$seller->address}}</span>

    <!-- Product Stats -->
    @if($post)
        <div class="seller-stats-container">
            <div class="stat-row">
                <div class="stat-item">
                    <i class="fas fa-calendar"></i>
                    <span class="stat-label">تاريخ النشر</span>
                    <span class="stat-value">{{$post->created_at?->format('Y-m-d')}}</span>
                </div>
                <div class="stat-item">
                    <i class="fa fa-eye"></i>
                    <span class="stat-label">المشاهدات</span>
                    <span class="stat-value">{{$post->views_count}}</span>
                </div>
            </div>
        </div>
    @endif

    <div class="divider my-1 "></div>
    <div class="d-flex justify-content-center gap-1">
        @auth
            @if(auth()->id()!= $seller->id)
                <form action="{{route('communities.store')}}" method="post">
                    @csrf
                    <input type="hidden" name="sellerId" value="{{ $seller?->id ??$post->user_id }}">
                    <button class="btn-green rounded bg-transparent d-flex justify-content-center align-items-center">
                        <i class="fa fa-comments text-black"></i>
                        <span class="small d-none d-md-inline-block  text-black">
                   تحدث معه
                </span>
                    </button>
                </form>
            @else
                <button class="btn-green rounded bg-transparent d-flex justify-content-center align-items-center" onclick="showToast('لا يمكنك إجراء محادثة مع نفسك','error')">
                    <i class="fa fa-comments text-black"></i>
                    <span class="small d-none d-md-inline-block  text-black">
                   تحدث معه
                </span>
                </button>
            @endif

        @else
            <button class="btn-green rounded bg-transparent d-flex justify-content-center align-items-center" onclick="showToast('يرجى تسجيل الدخول اولاً','error')">
                <i class="fa fa-comments text-black"></i>
                <span class="small d-none d-md-inline-block  text-black">
                   تحدث معه
                </span>
            </button>
        @endauth
@if($productId!=null)
        <button class="btn-green rounded d-flex justify-content-center align-items-center" type="button" onclick="clickWhats()">
            <i class="fa-brands fa-whatsapp"></i>
            <span class="small d-none d-md-inline-block  text-white">
                   واتس آب
                </span>
        </button>
        @else
            <a class="btn-green rounded d-flex justify-content-center align-items-center" href="https://wa.me/{{$seller->full_phone}}" target="_blank">
                <i class="fa-brands fa-whatsapp"></i>
                <span class="small d-none d-md-inline-block  text-white">
                   واتس آب
                </span>
            </a>
        @endif

    </div>
</div>

<script>
    function clickWhats() {
        const auth="{{auth()->check()}}";
            if (auth=="") {
            localStorage.removeItem('token');
                showToast('يرجى تسجيل الدخول اولاً','error')
                console.log(auth)
                return;
            }
        else if (localStorage.getItem('token')==null && auth!='' ) {

@auth localStorage.setItem('token', '{{auth()->user()->createToken('MyApp')->plainTextToken}}') @endauth
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
                   product_id: {{$productId}}
               })
           })
               .then(response => {

                   if (response) {

                       return response.json(); // نستخدم json() لأن الاستجابة الآن تكون ككائن JSON
                   }
                   throw new Error('Network response was not ok');
               })
               .then(data => {
console.log(data)
                   if(data?.length >3){
                       window.open(`https://wa.me/${data}`, '_blank');
                   }else{
                       throw new Error('خطأ في رقم الهاتف');
                   }

               })
               .catch(error => {
                   console.error('Error:', error);
                   showToast(error,'error');
               });


    }
</script>

<style>
    .seller-stats-container {
        margin: 12px 0;
        padding: 12px 0;
    }

    .stat-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 12px;
    }

    .stat-item {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 10px;
        background: linear-gradient(135deg, #f8f9fa 0%, #f0f1f3 100%);
        border-radius: 8px;
        text-align: center;
        transition: all 0.3s ease;
    }

    .stat-item:hover {
        background: linear-gradient(135deg, #e9ecef 0%, #dee2e6 100%);
        transform: translateY(-2px);
    }

    .stat-item i {
        font-size: 18px;
        color: #e30613;
        margin-bottom: 6px;
    }

    .stat-label {
        font-size: 11px;
        color: #6c757d;
        font-weight: 500;
        margin-bottom: 4px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .stat-value {
        font-size: 14px;
        color: #212529;
        font-weight: 600;
    }

    @media (max-width: 768px) {
        .stat-row {
            grid-template-columns: 1fr 1fr;
        }

        .stat-item {
            padding: 8px;
        }

        .stat-item i {
            font-size: 16px;
        }

        .stat-label {
            font-size: 10px;
        }

        .stat-value {
            font-size: 13px;
        }
    }
</style>
