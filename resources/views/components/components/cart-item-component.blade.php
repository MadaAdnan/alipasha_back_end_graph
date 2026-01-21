@props(['item'=>null])

<div class="card cart-item mb-3 w-100">
    <div class="card-body p-2">

        <!-- الصف الرئيسي بعرض كامل -->
        <div class="d-flex align-items-center w-100 gap-3">

            <!-- صورة المنتج -->
            <img
                src="{{ $item->product->getImage()}}"
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
                            {{ Str::limit($item->product->info, 50) }}
                        </small>
                    </div>

                    <!-- حذف (بدون action) -->
                    <form method="POST" action="{{route('carts.destroy',$item->id)}}">
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
                        <x-components.price-component :price="$item->product->price" :discount="$item->product->discount"
                                                      :isDiscount="$item->product->is_discount"/>
                    </span>

                    <div class="d-flex align-items-center gap-2">

                        <!-- إنقاص -->
                        <form method="POST" action="{{route('carts.store')}}">
                            @csrf
                            <input type="hidden" name="type" value="min">
                            <input type="hidden" name="productId" value="{{$item->product?->id}}">
                            <button class="btn btn-outline-secondary btn-sm"
                                {{ $item->qty <= 1 ? 'disabled' : '' }}>
                                <i class="fas fa-minus"></i>
                            </button>
                        </form>

                        <span class="qty">{{ $item->qty }}</span>

                        <!-- زيادة -->
                        <form method="POST"  action="{{route('carts.store')}}">
                            @csrf
                             <input type="hidden" name="productId" value="{{$item->product->id}}">
                            <button class="btn cart-btn btn-sm">
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
                @php
                    $price=$item->product->is_discount ? $item->product->discount :$item->product->price ;
 @endphp
                {{ number_format( $price* $item->qty, 2) }} $
            </strong>
        </div>

    </div>
</div>
