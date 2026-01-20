@props([
    'item'=>null
])
<div class="cart-container">

        <div class="cart-card card mb-3" data-product-id="{{ $item->product->id }}">
            <div class="card-body">
                <div class="row align-items-center">
                    <!-- صورة المنتج -->
                    <div class="col-md-2 col-3">
                        <img src="{{ $item->product->image ?? 'https://via.placeholder.com/100' }}"
                             alt="{{ $item->product->name }}"
                             class="img-fluid rounded">
                    </div>

                    <!-- معلومات المنتج -->
                    <div class="col-md-5 col-6">
                        <h5 class="card-title mb-1">{{ $item->product->name }}</h5>
                        <p class="text-muted mb-1 small">{{ Str::limit($item->product->description, 50) }}</p>
                        <p class="mb-0">
                            <span class="product-price" data-price="{{ $item->product->price }}">
                                {{ number_format($item->product->price, 2) }} ر.س
                            </span>
                        </p>
                    </div>

                    <!-- أدوات التحكم بالكمية -->
                    <div class="col-md-3 col-9">
                        <div class="quantity-control d-flex align-items-center">
                            <button class="btn btn-outline-secondary btn-sm decrease-qty"
                                    onclick="decreaseQuantity({{ $item->product->id }})"
                                {{ $item->quantity <= 1 ? 'disabled' : '' }}>
                                <i class="fas fa-minus"></i>
                            </button>

                            <input type="number"
                                   class="form-control text-center mx-2 quantity-input"
                                   value="{{ $item->quantity }}"
                                   min="1"
                                   max="99"
                                   id="quantity-{{ $item->product->id }}"
                                   onchange="updateQuantity({{ $item->product->id }}, this.value)">

                            <button class="btn btn-outline-secondary btn-sm increase-qty"
                                    onclick="increaseQuantity({{ $item->product->id }})">
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>
                    </div>

                    <!-- السعر الإجمالي وحذف المنتج -->
                    <div class="col-md-2 col-3 text-end">
                        <p class="mb-2 fw-bold total-price" id="total-{{ $item->product->id }}">
                            {{ number_format($item->product->price * $item->quantity, 2) }} ر.س
                        </p>
                        <button class="btn btn-danger btn-sm remove-item"
                                onclick="removeFromCart({{ $item->product->id }})">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
</div>
