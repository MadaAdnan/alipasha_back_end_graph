@props([
    'product'=>null
])
<div class="stat-item-modern cursor-pointer"   onclick="toggleLike({{auth()->id()}}, {{$product->id}})">
    <i class="fas fa-heart like-icon @if(!auth()->check() || !$product->is_like) text-gray @endif"
       data-user-id="{{auth()->id()}}"
       data-product-id="{{$product->id}}"
     ></i>
    <span id="likes-count-{{$product->id}}">{{\App\Helpers\GlobalHelper::formatNumber($product->likes_count)}}</span>
</div>

<script>
function toggleLike(userId, productId) {
    if (!userId) {
        Toastify({
            text: "This is a toast",
            duration: 3000,
            destination: "https://github.com/apvarun/toastify-js",
            newWindow: true,
            close: true,
            gravity: "top", // `top` or `bottom`
            position: "left", // `left`, `center` or `right`
            stopOnFocus: true, // Prevents dismissing of toast on hover
            style: {
                background: "linear-gradient(to right, #00b09b, #96c93d)",
            },
            onClick: function(){} // Callback after click
        }).showToast();
        window.location.href = '/login';
        return;
    }

    // إرسال طلب AJAX إلى نقطة النهاية
    fetch(`/api/like/${userId}/${productId}`, {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
            // ملاحظة: لا حاجة لـ Authorization لأن نقطة النهاية هذه تستخدم مصادقة الجلسة
        }
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
            const likesCountElement = document.getElementById(`likes-count-${productId}`);
            if (likesCountElement && data !== undefined) {
                // تحديث عدد الإعجابات
                likesCountElement.textContent = data;
            }

            // استخدام الحالة الفعلية من الاستجابة لتحديد فئة الأيقونة
            const likeIcon = document.querySelector(`.like-icon[data-product-id="${productId}"]`);
            if (likeIcon && data !== undefined) {
                // إذا كان المستخدم قد أعجب بالفعل، قم بإزالة فئة text-gray
                // إذا لم يعجب المستخدم، قم بإضافة فئة text-gray
                if (data) {
                    likeIcon.classList.remove('text-gray'); // مستخدم يحب المنتج
                } else {
                    likeIcon.classList.add('text-gray'); // مستخدم لا يحب المنتج
                }
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('حدث خطأ أثناء معالجة الطلب');
        });
}
</script>
