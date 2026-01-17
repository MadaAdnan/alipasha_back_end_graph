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
            'X-Requested-With': 'XMLHttpRequest',
            'Authorization': 'Bearer ' + localStorage.getItem('token'),
            // لا حاجة لـ Authorization لأن نقطة النهاية هذه تستخدم مصادقة الجلسة
        }
    })
    .then(response => {
        if (response.ok) {
            return response.text(); // نظرًا لأن نقطة النهاية تُرجع قيمة عددية بسيطة، نستخدم text()
        }
        throw new Error('Network response was not ok');
    })
    .then(data => {
        // التحديث بناءً على الحالة الجديدة
        const likesCountElement = document.getElementById(`likes-count-${productId}`);
        if (likesCountElement) {
            // تحديث عدد الإعجابات
            likesCountElement.textContent = data;
        }

        // تبديل أيقونة الإعجاب (إضافة أو إزالة الفئة)
        const likeIcon = document.querySelector(`.like-icon[data-product-id="${productId}"]`);
        if (likeIcon) {
            // تحقق مما إذا كانت الأيقونة تحتوي على فئة text-gray وقم بتبديلها
            if (likeIcon.classList.contains('text-gray')) {
                likeIcon.classList.remove('text-gray'); // إزالة الفئة عند الإعجاب
            } else {
                likeIcon.classList.add('text-gray'); // إضافة الفئة عند إلغاء الإعجاب
            }
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('حدث خطأ أثناء معالجة الطلب');
    });
}
</script>
