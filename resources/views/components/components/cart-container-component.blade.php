@props([
    'seller'=>null
])
<div class="card card-body my-2 ">


    <div class="row g-2">
        @if($items?->count()>0)
            @foreach($items as $product)
                <div class="col-12">
                    <x-components.cart-item-component :item="$product"/>
                </div>
            @endforeach
            <div class="col-12">
                <div class="d-flex justify-content-between info-data">
                    <span><i class="fa-solid fa-money-check-dollar"></i> إجمالي السعر</span>
                    @php
                        $total = $items->sum(function ($item) {
                            $p=$item->product;
                            $price=$p->is_discount?$p->discount:$p->price;
                            return $price * $item->qty;
                        });
                    @endphp
                    <span>{{$total}}</span>
                </div>
            </div>
            <div class="col-12">
                @php
                    $message='';
                    if($items->count()>1){
                        $message="السلام عليكم ورحمة الله وبركاته \n";
                        foreach ($items as $item){
                            $product=$item->product;
                             $message.="معرف المنتج : ".$product->id."\n";
                             $message.="العنصر : ".$product->name."\n";
                              $price=$product->is_discount?$product->discount:$product->price;
                              $message.="السعر : ".$price."\n";
                              $message.="الكمية : ".$item->qty."\n";
                              $message.="السعر الكلي : ".$price * $item->qty."\n";
                              $message.="------------";
                               }
                    }
                @endphp
                <a href="https://wa.me/{{$seller->full_phone}}?text={{$message}}" class="btn"><i class="fa-brand fa-whatsapp"></i> طلب من خلال واتس آب</a>
            </div>
        @else
            <div class="col-12">
                لا يوجد عناصر في السلة
            </div>
        @endif

        </div>

</div>

