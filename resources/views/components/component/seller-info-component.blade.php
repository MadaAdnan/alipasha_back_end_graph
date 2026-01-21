@props([
    'seller'=>null
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

        <button class="btn-green rounded" type="button" onclick="clickWhats('{{$seller->id}}')">
            <i class="fa-brands fa-whatsapp"></i>
            <span class="small d-none d-md-inline-block mx-1">
                   واتس آب
                </span>
        </button>

    </div>
</div>

<script>
    function clickWhats(userId) {
        if (localStorage.getItem('token')==null) {
            // إذا لم يكن المستخدم قد سجل الدخول، قم بإعادة التوجيه إلى صفحة تسجيل الدخول
            window.location.href = '/login';
            return;
        }

        // إرسال طلب AJAX إلى نقطة النهاية
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
                user_id: userId
            })
        })
            .then(response => {
                if (response.ok) {
                    console.log('Response:', response)
                    return response.json(); // نستخدم json() لأن الاستجابة الآن تكون ككائن JSON
                }
                throw new Error('Network response was not ok');
            })
            .then(data => {
                console.log('Data:', data)
                window.location.href = `https://wa.me/${data.full_phone}`;
            })
            .catch(error => {
                console.error('Error:', error);
                alert('حدث خطأ أثناء الإنتقال');
            });
    }
</script>
