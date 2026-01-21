@props([
    'seller'=>null,
    'productId'=>null,
])
<div class="card card-body">
    <h6 class="text-gray fw-bold my-1">معلومات المعلن</h6>
    <x-components.seller-name-component :seller="$seller" :image="$seller->getImage()"/>
    <span class="small text-muted"><i class="fa fa-location-dot"></i> {{$seller->address}}</span>
    <div class="divider my-1 "></div>
    <div class="d-flex justify-content-center gap-1">
        <form action="{{route('communities.store')}}">
            @csrf
            <button class="btn-green rounded bg-transparent">
                <i class="fa fa-comments text-black"></i>
                <span class="small d-none d-md-inline-block mx-1 text-black">
                   تحدث معه
                </span>
            </button>
        </form>
@if($productId!=null)
        <button class="btn-green rounded" type="button" onclick="clickWhats()">
            <i class="fa-brands fa-whatsapp"></i>
            <span class="small d-none d-md-inline-block mx-1 text-white">
                   واتس آب
                </span>
        </button>
        @else
            <a class="btn-green rounded text-center d-inline-block" href="https://wa.me/{{$seller->full_phone}}" target="_blank">
                <i class="fa-brands fa-whatsapp"></i>
                <span class="small d-none d-md-inline-block mx-1 text-white">
                   واتس آب
                </span>
            </a>
        @endif

    </div>
</div>

<script>
    function clickWhats() {
        if (localStorage.getItem('token')==null) {
            // إذا لم يكن المستخدم قد سجل الدخول، قم بإعادة التوجيه إلى صفحة تسجيل الدخول
            window.location.href = '/login';
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
                   product_id: {{$productId}}
               })
           })
               .then(response => {
                   console.log('Response:', response)
                   if (response) {
                       console.log('Response:', response)
                       return response.json(); // نستخدم json() لأن الاستجابة الآن تكون ككائن JSON
                   }
                   throw new Error('Network response was not ok');
               })
               .then(data => {
                   console.log('Data:', data)
                   if(data!==''){
                       window.open(`https://wa.me/${data}`, '_blank');
                   }else{
                       throw new Error('خطأ في رقم الهاتف');
                   }

               })
               .catch(error => {
                   console.error('Error:', error);
                   alert('حدث خطأ أثناء الإنتقال');
               });


    }
</script>
