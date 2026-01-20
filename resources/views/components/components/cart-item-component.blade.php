@props(['item'=>null])

<div class="card cart-item mb-3 w-100">
    <div class="card-body p-2">

        <!-- الصف الرئيسي بعرض كامل -->
        <div class="d-flex align-items-center w-100 gap-3">

            <!-- صورة المنتج -->
            <img
                src="{{ $item->product->image ?? 'https://via.placeholder.com/100' }}"
                alt="{{ $item->product->name }}"
                class="cart-img"
            >

            <!-- المحتوى يتمدد بعرض الصندوق -->
            <div class="cart-content flex-grow-1">

                <!-- العنوان + حذف -->
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h6 class="mb-1">{{ $item->product->name }}</h6>
                        <small class="text-muted d-block">
                            {{ Str::limit($item->product->description, 50) }}
                        </small>
                    </div>

                    <!-- حذف (بدون action) -->
                    <form method="POST">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger btn-sm">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                </div>

                <!-- السعر + التحكم بالكمية -->
                <div class="d-flex justify-content-between align-items-center mt-2">

                    <span class="price">
                        {{ number_format($item->product->price, 2) }} ر.س
                    </span>

                    <div class="d-flex align-items-center gap-2">

                        <!-- إنقاص -->
                        <form method="POST">
                            @csrf
                            <button class="btn btn-outline-secondary btn-sm"
                                {{ $item->quantity <= 1 ? 'disabled' : '' }}>
                                <i class="fas fa-minus"></i>
                            </button>
                        </form>

                        <span class="qty">{{ $item->quantity }}</span>

                        <!-- زيادة -->
                        <form method="POST">
                            @csrf
                            <button class="btn btn-outline-secondary btn-sm">
                                <i class="fas fa-plus"></i>
                            </button>
                        </form>

                    </div>
                </div>

            </div>
        </div>

        <!-- الإجمالي -->
        <div class="text-end mt-2">
            <small class="text-muted">الإجمالي:</small>
            <strong>
                {{ number_format($item->product->price * $item->quantity, 2) }} ر.س
            </strong>
        </div>

    </div>
</div>
