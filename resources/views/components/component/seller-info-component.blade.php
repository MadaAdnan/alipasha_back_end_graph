@props([
    'seller'=>null,
    'productId'=>null,
])
<div class="card card-body seller-data">
    <div class="d-flex justify-content-between align-items-center">
        <h6 class="text-gray fw-bold my-1">معلومات المعلن</h6>
        @auth
            @php
                $following=auth()->user()->followers()->pluck('seller_id')->toArray();
                $isFollowing=$following==null?false:in_array($seller->id,$following);
            @endphp
        <form @if(!$isFollowing) action="{{route('following-to-seller',$seller->id)}}" @endif method="POST">
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
    <div class="divider my-1 "></div>
    <div class="d-flex justify-content-center gap-1">
        @auth
        <form action="{{route('communities.store')}}">
            @csrf
            <button class="btn-green rounded bg-transparent d-flex justify-content-center align-items-center">
                <i class="fa fa-comments text-black"></i>
                <span class="small d-none d-md-inline-block  text-black">
                   تحدث معه
                </span>
            </button>
        </form>
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
            if (auth==false) {
            localStorage.removeItem('token');
                showToast('يرجى تسجيل الدخول اولاً','error')
            }
        else if (localStorage.getItem('token')==null  ) {
            console.log("{{auth()->check()}}")
         //   localStorage.setItem('token',"{{auth()->user()->createToken('user')->plainTextToken}}")
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

                   if(data!==''){
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
