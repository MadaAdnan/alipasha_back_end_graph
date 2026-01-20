@props([
    'seller'=>null
])
<div class="card card-body my-2 ">


        <div class="row g-2">
            @if($items?->count()>0)
            @foreach($items as $product)
                <div class="col-lg-3 col-md-4 col-sm-6 col-6">
                    <x-components.cart-item-component :item="$product"/>
                </div>
            @endforeach
            @endif
            <div class="col-12">
                لا يوجد عناصر في السلة
            </div>
        </div>

</div>
<script>
    // CSRF Token لطلبات AJAX
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    // زيادة كمية المنتج
    async function increaseQuantity(productId) {
        const quantityInput = document.getElementById(`quantity-${productId}`);
        const currentQty = parseInt(quantityInput.value);
        const maxQty = parseInt(quantityInput.max);

        if (currentQty < maxQty) {
            const newQty = currentQty + 1;
            quantityInput.value = newQty;

            // تحديث السعر الإجمالي
            updateProductTotal(productId, newQty);

            // تحديث في السيرفر
            await updateCart(productId, newQty, 'increase');

            // تحديث زر النقصان
            toggleDecreaseButton(productId, newQty);
        }
    }

    // إنقاص كمية المنتج
    async function decreaseQuantity(productId) {
        const quantityInput = document.getElementById(`quantity-${productId}`);
        const currentQty = parseInt(quantityInput.value);
        const minQty = parseInt(quantityInput.min);

        if (currentQty > minQty) {
            const newQty = currentQty - 1;
            quantityInput.value = newQty;

            // تحديث السعر الإجمالي
            updateProductTotal(productId, newQty);

            // تحديث في السيرفر
            await updateCart(productId, newQty, 'decrease');

            // تحديث زر النقصان
            toggleDecreaseButton(productId, newQty);
        }
    }

    // تحديث الكمية يدوياً
    async function updateQuantity(productId, newValue) {
        const quantityInput = document.getElementById(`quantity-${productId}`);
        const minQty = parseInt(quantityInput.min);
        const maxQty = parseInt(quantityInput.max);

        // التأكد من أن القيمة ضمن الحدود
        let newQty = parseInt(newValue);
        if (isNaN(newQty) || newQty < minQty) newQty = minQty;
        if (newQty > maxQty) newQty = maxQty;

        quantityInput.value = newQty;

        // تحديث السعر الإجمالي
        updateProductTotal(productId, newQty);

        // تحديث في السيرفر
        await updateCart(productId, newQty, 'update');

        // تحديث زر النقصان
        toggleDecreaseButton(productId, newQty);
    }

    // تحديث السعر الإجمالي للمنتج
    function updateProductTotal(productId, quantity) {
        const productCard = document.querySelector(`[data-product-id="${productId}"]`);
        const pricePerUnit = parseFloat(productCard.querySelector('.product-price').dataset.price);
        const totalElement = document.getElementById(`total-${productId}`);

        const total = pricePerUnit * quantity;
        totalElement.textContent = total.toFixed(2) + ' ر.س';

        // تحديث المجموع الكلي للسلة
        updateCartTotal();
    }

    // تحديث المجموع الكلي للسلة
    function updateCartTotal() {
        const productCards = document.querySelectorAll('.cart-card');
        let cartTotal = 0;

        productCards.forEach(card => {
            const productId = card.dataset.productId;
            const quantity = parseInt(document.getElementById(`quantity-${productId}`).value);
            const pricePerUnit = parseFloat(card.querySelector('.product-price').dataset.price);

            cartTotal += pricePerUnit * quantity;
        });

        const cartTotalElement = document.getElementById('cart-total');
        if (cartTotalElement) {
            cartTotalElement.textContent = cartTotal.toFixed(2) + ' ر.س';
        }
    }

    // تبديل حالة زر النقصان
    function toggleDecreaseButton(productId, quantity) {
        const decreaseBtn = document.querySelector(`[onclick="decreaseQuantity(${productId})"]`);
        const minQty = 1;

        if (decreaseBtn) {
            decreaseBtn.disabled = quantity <= minQty;

            if (quantity <= minQty) {
                decreaseBtn.classList.add('disabled');
            } else {
                decreaseBtn.classList.remove('disabled');
            }
        }
    }

    // تحديث السلة في السيرفر
    async function updateCart(productId, quantity, action) {
        try {
            let url = '';

            switch(action) {
                case 'increase':
                    url = `/add-to-cart/${productId}`;
                    break;
                case 'decrease':
                    url = `/sub-from-cart/${productId}`;
                    break;
                case 'update':
                    // إذا كنت تريد نقطة نهاية لتحديث الكمية مباشرة
                    url = `/update-cart/${productId}`;
                    break;
            }

            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                }
            });

            if (!response.ok) {
                throw new Error('فشل تحديث السلة');
            }

            const data = await response.json();

            // تحديث عداد السلة في الهيدر (إذا كان موجوداً)
            if (data.cart_count !== undefined) {
                updateCartCounter(data.cart_count);
            }

            // إظهار رسالة نجاح
            showToast('تم تحديث السلة بنجاح', 'success');

        } catch (error) {
            console.error('Error updating cart:', error);
            showToast('حدث خطأ في تحديث السلة', 'error');
        }
    }

    // حذف المنتج من السلة
    async function removeFromCart(productId) {
        if (!confirm('هل تريد حذف هذا المنتج من السلة؟')) {
            return;
        }

        try {
            const response = await fetch(`/remove-from-cart/${productId}`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                }
            });

            if (!response.ok) {
                throw new Error('فشل حذف المنتج');
            }

            const data = await response.json();

            // إزالة الكارد من الواجهة
            const productCard = document.querySelector(`[data-product-id="${productId}"]`);
            if (productCard) {
                productCard.remove();
            }

            // تحديث عداد السلة
            if (data.cart_count !== undefined) {
                updateCartCounter(data.cart_count);
            }

            // تحديث المجموع الكلي
            updateCartTotal();

            // إظهار رسالة نجاح
            showToast('تم حذف المنتج من السلة', 'success');

            // إذا كانت السلة فارغة، عرض رسالة
            const cartContainer = document.querySelector('.cart-container');
            const cartCards = cartContainer.querySelectorAll('.cart-card');
            if (cartCards.length === 0) {
                showEmptyCartMessage();
            }

        } catch (error) {
            console.error('Error removing item:', error);
            showToast('حدث خطأ في حذف المنتج', 'error');
        }
    }

    // تحديث عداد السلة في الهيدر
    function updateCartCounter(count) {
        const cartCounter = document.getElementById('cart-counter');
        if (cartCounter) {
            cartCounter.textContent = count;
            cartCounter.style.display = count > 0 ? 'inline-block' : 'none';
        }
    }

    // إظهار رسالة السلة الفارغة
    function showEmptyCartMessage() {
        const cartContainer = document.querySelector('.cart-container');
        cartContainer.innerHTML = `
        <div class="text-center py-5">
            <i class="fas fa-shopping-cart fa-3x text-muted mb-3"></i>
            <h4>سلة المشتريات فارغة</h4>
            <p class="text-muted">أضف بعض المنتجات إلى سلة مشترياتك</p>
            <a href="/products" class="btn btn-primary">
                <i class="fas fa-store me-2"></i> تصفح المنتجات
            </a>
        </div>
    `;
    }

    // إظهار رسائل Toast
    function showToast(message, type = 'info') {
        // إنشاء toast element
        const toastId = 'toast-' + Date.now();
        const toastHtml = `
        <div id="${toastId}" class="toast align-items-center text-bg-${type} border-0" role="alert">
            <div class="d-flex">
                <div class="toast-body">
                    ${message}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        </div>
    `;

        // إضافة toast إلى container
        const toastContainer = document.getElementById('toast-container') || createToastContainer();
        toastContainer.innerHTML += toastHtml;

        // إظهار toast
        const toastElement = document.getElementById(toastId);
        const toast = new bootstrap.Toast(toastElement, { delay: 3000 });
        toast.show();

        // إزالة toast بعد إخفائه
        toastElement.addEventListener('hidden.bs.toast', function () {
            this.remove();
        });
    }

    // إنشاء container لـ toasts إذا لم يكن موجوداً
    function createToastContainer() {
        const container = document.createElement('div');
        container.id = 'toast-container';
        container.className = 'toast-container position-fixed bottom-0 end-0 p-3';
        document.body.appendChild(container);
        return container;
    }

    // عملية الشراء
    function checkout() {
        // يمكنك توجيه المستخدم إلى صفحة الدفع
        window.location.href = '/checkout';
    }

    // تهيئة الأحداث عند تحميل الصفحة
    document.addEventListener('DOMContentLoaded', function() {
        // إضافة تحقق من صحة الإدخال
        document.querySelectorAll('.quantity-input').forEach(input => {
            input.addEventListener('blur', function() {
                const productId = this.id.replace('quantity-', '');
                const value = parseInt(this.value);

                if (isNaN(value) || value < 1) {
                    this.value = 1;
                    updateQuantity(productId, 1);
                } else if (value > 99) {
                    this.value = 99;
                    updateQuantity(productId, 99);
                }
            });
        });
    });
</script>
