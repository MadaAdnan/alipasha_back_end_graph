@props(['item'=>null])

<div class="card cart-item mb-2">
    <div class="card-body p-2">

        <div class="d-flex align-items-center gap-3">

            <!-- صورة المنتج -->
            <div class="cart-image">
                <img
                    src="{{ $item->product->image ?? 'https://via.placeholder.com/100' }}"
                    alt="{{ $item->product->name }}"
                >
            </div>

            <!-- تفاصيل المنتج -->
            <div class="cart-details flex-grow-1">
                <h6 class="mb-1">{{ $item->product->name }}</h6>

                <small class="text-muted d-block">
                    {{ Str::limit($item->product->description, 45) }}
                </small>

                <div class="d-flex justify-content-between align-items-center mt-2">

                    <!-- السعر -->
                    <span class="price">
                        {{ number_format($item->product->price, 2) }} ر.س
                    </span>

                    <!-- التحكم بالكمية -->
                    <div class="quantity-box d-flex align-items-center gap-1">

                        <!-- تقليل -->
                        <form method="POST" action="">
                            @csrf
                            <button class="btn btn-outline-secondary btn-sm"
                                {{ $item->quantity <= 1 ? 'disabled' : '' }}>
                                <i class="fas fa-minus"></i>
                            </button>
                        </form>

                        <!-- عرض الكمية -->
                        <span class="quantity-value">
                            {{ $item->quantity }}
                        </span>

                        <!-- زيادة -->
                        <form method="POST" action="{{ route('cart.increase', $item->product->id) }}">
                            @csrf
                            <button class="btn btn-outline-secondary btn-sm">
                                <i class="fas fa-plus"></i>
                            </button>
                        </form>

                    </div>

                </div>
            </div>

            <!-- حذف المنتج -->
            <form method="POST" action="">
                @csrf
                @method('DELETE')

                <button class="btn btn-danger btn-sm">
                    <i class="fas fa-trash"></i>
                </button>
            </form>

        </div>

        <!-- الإجمالي -->
        <div class="cart-total text-end mt-2">
            <small class="text-muted">الإجمالي:</small>
            <strong>
                {{ number_format($item->product->price * $item->quantity, 2) }} ر.س
            </strong>
        </div>

    </div>
</div>
