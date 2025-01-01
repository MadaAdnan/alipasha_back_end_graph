<p class="px-4 py-3 bg-gray-100 rounded-lg">
    <span class="font-medium">
       المنتجات
    </span>

    <table class="table table-auto" style="width: 100%">
    <tr>
        <td>صورة</td>
        <td>المنتج</td>
        <td>الكمية </td>
        <td>التوصيل</td>
    </tr>
       @foreach($getRecord()->items as $item)
           <tr>
               <td><img style="width: 75px;border-radius: 50%" src="{{$item->product->getFirstMediaUrl('images')}}" alt=""></td>
               <td>{{$item->product->name}}</td>
               <td>{{$item->qty}}</td>
               <td>{{\App\Enums\IsActiveEnum::tryFrom($item->product?->is_delivery)->getLabel()}}</td>
           </tr>

       @endforeach
    </table>
</p>
