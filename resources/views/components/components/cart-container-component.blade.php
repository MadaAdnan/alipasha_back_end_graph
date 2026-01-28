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
                    $dataForApi=[];
                        $message='';
                        $sumTotal=0;
                        if($items->count()>0){
                            $message="السلام عليكم ورحمة الله وبركاته \n";
                            foreach ($items as $item){

                                $product=$item->product;
                                 $message.="معرف المنتج : ".$product->id."\n";
                                 $message.="المنتج : ".$product->name."\n";
                                  $price=$product->is_discount?$product->discount:$product->price;
                                  $message.="السعر : ".$price."\n";
                                  $message.="الكمية : ".$item->qty."\n";
                                  $total=$price * $item->qty;
                                  $sumTotal+=$total;
                                  $message.="الإجمالي : ".$total."\n";
                                  $message.="______________________________________\n";
                                   $dataForApi[]=[
                                    "product_id"=>$item->product_id,
                                    'qty'=>$item->qty,
                                    'price'=>$price,
                                    'total'=>$total
                                    ];
                                   }
                        }
                          $message.="قيمة الطلب : ".$sumTotal;
                @endphp
                <button id="submit" type="button"
                        data-href="https://wa.me/{{$seller?->full_phone}}?text={{urlencode($message)}}"
                        class="btn btn-green"><i class="fa-brand fa-whatsapp"></i> طلب من خلال واتس آب
                </button>
            </div>
        @else
            <div class="col-12">
                لا يوجد عناصر في السلة
            </div>
        @endif

    </div>

</div>
<script>
    let submit = document.getElementById('submit');
    submit.addEventListener('click', function () {
        console.log(this.dataset.href)
        fetch(`/api/orders`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                ...(localStorage.getItem('token') && {
                    'Authorization': 'Bearer ' + localStorage.getItem('token')
                })
            },
            body: JSON.stringify({
                data:@json($dataForApi),
                seller_id:@json($seller->id)
            })
        })
            .then(response => {

                if (response) {

                    return response.json(); // نستخدم json() لأن الاستجابة الآن تكون ككائن JSON
                }
                throw new Error('Network response was not ok');
            })
            .then(data => {
                console.log(data)
                if(data.status=='success'){
                    window.location.href = this.dataset.href;
                   
                }


            })
            .catch(error => {
                console.error('Error:', error);
                showToast(error, 'error');
            });
    })

</script>

