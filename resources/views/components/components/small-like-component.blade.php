@props([
    'product'=>null
])
<div class="stat-item-modern">
    <i class="fas fa-heart like-icon @if(!auth()->check() || !$product->is_like) text-gray @endif"
       data-user-id="{{auth()->id()}}"
       data-product-id="{{$product->id}}"
       onclick="toggleLike({{auth()->id()}}, {{$product->id}})"></i>
    <span id="likes-count-{{$product->id}}">{{\App\Helpers\GlobalHelper::formatNumber($product->likes_count)}}</span>
</div>

<script>
function toggleLike(userId, productId) {
    if (!userId) {
        // إذا لم يكن المستخدم قد سجل الدخول، قم بإعادة التوجيه إلى صفحة تسجيل الدخول
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
            // التحديث بناءً على الحالة الجديدة
            const likesCountElement = document.getElementById(`likes-count-${productId}`);
            if (likesCountElement && data !== undefined) {
                // تحديث عدد الإعجابات
                likesCountElement.textContent = data;
            }

            // استخدام الحالة الفعلية من الاستجابة لتحديد فئة الأيقونة
            const likeIcon = document.querySelector(`.like-icon[data-product-id="${productId}"]`);
            if (likeIcon && data.is_liked !== undefined) {
                // إذا كان المستخدم قد أعجب بالفعل، قم بإزالة فئة text-gray
                // إذا لم يعجب المستخدم، قم بإضافة فئة text-gray
                if (data.is_liked) {
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
