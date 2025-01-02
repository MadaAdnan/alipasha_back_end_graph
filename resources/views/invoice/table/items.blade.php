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
               <td><img style="width: 50px;border-radius: 50%;aspect-ratio: 1/1" src="{{$item->product?->getFirstMediaUrl('images')}}" alt=""></td>
               <td><a class="fi-ta-header-cell-label text-sm font-semibold text-gray-950 dark:text-white" href="{{$item->product?->id !=null?\App\Filament\Resources\ProductResource::getUrl('edit',['record'=>$item->product?->id]):''}}">{{$item->product?->name}}</a></td>
               <td class="fi-ta-header-cell-label text-sm font-semibold text-gray-950 dark:text-white">{{$item->qty}}</td>
               <td class="fi-ta-header-cell-label text-sm font-semibold text-gray-950 dark:text-white">{{\App\Enums\IsActiveEnum::tryFrom($item->product?->is_delivery)->getLabel()}}</td>

           </tr>

       @endforeach
    </table>
</p>
