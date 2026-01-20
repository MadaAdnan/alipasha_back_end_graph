@props(['item'=>null])

<div class="card cart-card mb-2" data-product-id="{{ $item->product->id }}">
    <div class="card-body p-2">

        <div class="d-flex align-items-center gap-2">

            <!-- صورة المنتج -->
            <img
                src="{{ $item->product->image ?? 'https://via.placeholder.com/100' }}"
                alt="{{ $item->product->name }}"
                class="rounded"
                style="width:60px;height:60px;object-fit:cover"
            >

            <!-- معلومات المنتج -->
            <div class="flex-grow-1">
                <h6 class="mb-1">{{ $item->product->name }}</h6>
                <small class="text-muted d-block">
                    {{ Str::limit($item->product->description, 40) }}
                </small>

                <div class="d-flex justify-content-between align-items-center mt-1">

                    <!-- السعر -->
                    <span class="fw-bold text-success">
                        {{ number_format($item->product->price, 2) }} ر.س
                    </span>

                    <!-- التحكم بالكمية -->
                    <div class="d-flex align-items-center">
                        <button class="btn btn-outline-secondary btn-sm px-2"
                                onclick="decreaseQuantity({{ $item->product->id }})"
                            {{ $item->quantity <= 1 ? 'disabled' : '' }}>
                            −
                        </button>

                        <input type="number"
                               class="form-control form-control-sm text-center mx-1"
                               style="width:50px"
                               value="{{ $item->quantity }}"
                               min="1"
                               max="99"
                               id="quantity-{{ $item->product->id }}"
                               onchange="updateQuantity({{ $item->product->id }}, this.value)">

                        <button class="btn btn-outline-secondary btn-sm px-2"
                                onclick="increaseQuantity({{ $item->product->id }})">
                            +
                        </button>
                    </div>

                </div>
            </div>

            <!-- حذف -->
            <button class="btn btn-danger btn-sm"
                    onclick="removeFromCart({{ $item->product->id }})">
                <i class="fas fa-trash"></i>
            </button>

        </div>

        <!-- الإجمالي -->
        <div class="text-end mt-2">
            <small class="text-muted">الإجمالي:</small>
            <strong id="total-{{ $item->product->id }}">
                {{ number_format($item->product->price * $item->quantity, 2) }} ر.س
            </strong>
        </div>

    </div>
</div>
