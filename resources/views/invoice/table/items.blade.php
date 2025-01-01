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
        <td>أحداث</td>
    </tr>
       @foreach($getRecord()->items as $item)
           <tr>
               <td><img style="width: 50px;border-radius: 50%;aspect-ratio: 1/1" src="{{$item->product->getFirstMediaUrl('images')}}" alt=""></td>
               <td>{{$item->product->name}}</td>
               <td>{{$item->qty}}</td>
               <td>{{\App\Enums\IsActiveEnum::tryFrom($item->product?->is_delivery)->getLabel()}}</td>
               <td>
                   <a class="fi-btn relative grid-flow-col items-center justify-center font-semibold outline-none transition duration-75 focus-visible:ring-2 rounded-lg fi-color-custom fi-btn-color-primary fi-color-primary fi-size-md fi-btn-size-md gap-1.5 px-3 py-2 text-sm inline-grid shadow-sm bg-custom-600 text-white hover:bg-custom-500 focus-visible:ring-custom-500/50 dark:bg-custom-500 dark:hover:bg-custom-400 dark:focus-visible:ring-custom-400/50 fi-ac-action fi-ac-btn-action" href="{{\App\Filament\Resources\ProductResource::getUrl('edit',['record'=>$item->product->id])}}"><span class="color:black">ذهاب للمنتج</span></a>
               </td>
           </tr>

       @endforeach
    </table>
</p>
