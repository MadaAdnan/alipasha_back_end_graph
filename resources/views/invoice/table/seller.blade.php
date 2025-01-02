<p class="divide-y divide-gray-200 dark:divide-white/5">
    <span class="font-medium">
         <span class="fi-ta-header-cell-label text-sm font-semibold text-gray-950 dark:text-white">التاجر</span>

    </span>

    <table class="table table-auto" style="width: 100%">
    <tr class="divide-y divide-gray-200 dark:divide-white/5">
        <td><a class="fi-ta-header-cell-label text-sm font-semibold text-gray-950 dark:text-white" href="{{\App\Filament\Resources\UserResource::getUrl('edit',['record'=>$getRecord()->seller->id])}}">{{$getRecord()->seller->name}}</a></td>
        <td><a class="fi-ta-header-cell-label text-sm font-semibold text-gray-950 dark:text-white" href="https://wa.me/{{$getRecord()->seller->phone}}">{{$getRecord()->seller->phone}}</a></td>
        <td class="fi-ta-header-cell-label text-sm font-semibold text-gray-950 dark:text-white">{{$getRecord()->seller->address}} </td>
        <td class="fi-ta-header-cell-label text-sm font-semibold text-gray-950 dark:text-white">{{$getRecord()->seller->city?->name}}</td>
    </tr>

    </table>
</p>
