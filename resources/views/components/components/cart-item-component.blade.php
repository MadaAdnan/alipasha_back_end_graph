<div class="cart-container">
    @foreach($cartItems as $item)
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
    @endforeach

    <!-- السعر الإجمالي -->
    @if(count($cartItems) > 0)
        <div class="cart-summary card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-8 text-end">
                        <h5 class="mb-0">المجموع:</h5>
                    </div>
                    <div class="col-md-4">
                        <h4 class="text-success mb-0" id="cart-total">
                            {{ number_format($total, 2) }} ر.س
                        </h4>
                    </div>
                </div>
                <div class="mt-3">
                    <button class="btn btn-primary btn-lg w-100" onclick="checkout()">
                        <i class="fas fa-shopping-cart me-2"></i> إتمام الشراء
                    </button>
                </div>
            </div>
        </div>
    @else
        <div class="text-center py-5">
            <i class="fas fa-shopping-cart fa-3x text-muted mb-3"></i>
            <h4>سلة المشتريات فارغة</h4>
            <p class="text-muted">أضف بعض المنتجات إلى سلة مشترياتك</p>
            <a href="{{ route('products.index') }}" class="btn btn-primary">
                <i class="fas fa-store me-2"></i> تصفح المنتجات
            </a>
        </div>
    @endif
</div>
