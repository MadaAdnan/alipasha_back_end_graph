
<table class="table table-auto divide-y divide-gray-200 dark:divide-white/5" style="width: 100%">
    <tr class="divide-y divide-gray-200 dark:divide-white/5 border border-2">
        <td class="fi-ta-header-cell-label text-sm font-semibold text-gray-950 dark:text-white text-center" colspan="4">
            <span class="fi-ta-header-cell-label text-sm font-semibold text-gray-950 dark:text-white">المنتجات</span>
        </td>
    </tr>
    <tr class="divide-y divide-gray-200 dark:divide-white/5 border border-2">
        <td class="fi-ta-header-cell-label text-sm font-semibold text-gray-950 dark:text-white">صورة</td>
        <td class="fi-ta-header-cell-label text-sm font-semibold text-gray-950 dark:text-white">المنتج</td>
        <td class="fi-ta-header-cell-label text-sm font-semibold text-gray-950 dark:text-white">الكمية </td>
        <td class="fi-ta-header-cell-label text-sm font-semibold text-gray-950 dark:text-white">التوصيل</td>

    </tr>
       @foreach($getRecord()->items as $item)
           <tr class="divide-y divide-gray-200 dark:divide-white/5 border border-2">
               <td><img style="width: 50px;border-radius: 50%;aspect-ratio: 1/1" src="{{$item->product?->getFirstMediaUrl('images')}}" alt=""></td>
               <td><a class="fi-ta-header-cell-label text-sm font-semibold text-gray-950 dark:text-white" href="{{$item->product?->id !=null?\App\Filament\Resources\ProductResource::getUrl('edit',['record'=>$item->product?->id]):''}}">{{$item->product?->name}}</a></td>
               <td class="fi-ta-header-cell-label text-sm font-semibold text-gray-950 dark:text-white">{{$item->qty}}</td>
               <td class="fi-ta-header-cell-label text-sm font-semibold text-gray-950 dark:text-white">{{\App\Enums\IsActiveEnum::tryFrom($item->product?->is_delivery)->getLabel()}}</td>

           </tr>

       @endforeach
    </table>

