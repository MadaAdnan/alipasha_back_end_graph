
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
               <td><img style="width: 50px;border-radius: 50%;aspect-ratio: 1/1" src="@if($item->product?->hasMedia('images')) {{$item->product?->getFirstMediaUrl('images')}} @else {{$item->product?->getFirstMediaUrl('image')}} @endif" alt=""></td>
               <td><a class="fi-ta-header-cell-label text-sm font-semibold text-gray-950 dark:text-white" href="{{$item->product?->id !=null?\App\Filament\Resources\ProductResource::getUrl('edit',['record'=>$item->product?->id]):''}}">{{$item->product?->name}}</a></td>
               <td class="fi-ta-header-cell-label text-sm font-semibold text-gray-950 dark:text-white">{{$item->qty}}</td>
               @php
                   $status=$item->product?->is_delivery && $item->product?->city?->is_delivery &&$getRecord()->user?->city?->is_delivery;
               @endphp
               <td class="fi-ta-header-cell-label text-sm font-semibold  text-white text-center"  style=" background-color: @if($status) #4ade80 @else #92400e @endif">{{\App\Enums\IsActiveEnum::tryFrom($status)->getLabel()}}</td>

           </tr>

       @endforeach
    <tr class="divide-y divide-gray-200 dark:divide-white/5 border border-2">
        <td colspan="1" class="fi-ta-header-cell-label text-sm font-semibold text-gray-950 dark:text-white">
            المجموع :
        </td>
        <td  class="fi-ta-header-cell-label text-sm font-semibold text-gray-950 dark:text-white">
            {{$getRecord()->total}}
        </td>
        <td colspan="1" class="fi-ta-header-cell-label text-sm font-semibold text-gray-950 dark:text-white">
            أجور الشحن :
        </td>
        <td  class="fi-ta-header-cell-label text-sm font-semibold text-gray-950 dark:text-white">
            {{$getRecord()->shipping}}
        </td>
    </tr>
    <tr class="divide-y divide-gray-200 dark:divide-white/5 border border-2">
        <td colspan="1" class="fi-ta-header-cell-label text-sm font-semibold text-gray-950 dark:text-white">
            المجموع العام
        </td>
        <td colspan="3" class="fi-ta-header-cell-label text-sm font-semibold text-gray-950 dark:text-white" style="padding: 3px; background-color: darkorange; color: white">
            {{$getRecord()->shipping+ $getRecord()->total }}
        </td>
    </tr>
    <tr>
        <td colspan="1" class="fi-ta-header-cell-label text-sm font-semibold text-gray-950 dark:text-white">ملاحظات التاجر</td>
        <td colspan="3" class="fi-ta-header-cell-label text-sm font-semibold text-gray-950 dark:text-white">{{$getRecord()->seller_note}}</td>
    </tr>
    </table>

