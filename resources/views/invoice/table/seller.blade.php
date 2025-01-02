<p class="px-4 py-3 bg-gray-100 rounded-lg">
    <span class="font-medium">
       التاجر
    </span>

    <table class="table table-auto" style="width: 100%">
    <tr>
        <td><a class="fi-ta-header-cell-label text-sm font-semibold text-gray-950 dark:text-white" href="{{\App\Filament\Resources\UserResource::getUrl('edit',['record'=>$getRecord()->seller->id])}}">{{$getRecord()->seller->name}}</a></td>
        <td><a class="fi-ta-header-cell-label text-sm font-semibold text-gray-950 dark:text-white" href="https://wa.me/{{$getRecord()->seller->phone}}">{{$getRecord()->seller->phone}}</a></td>
        <td class="fi-ta-header-cell-label text-sm font-semibold text-gray-950 dark:text-white">{{$getRecord()->seller->address}} </td>
        <td class="fi-ta-header-cell-label text-sm font-semibold text-gray-950 dark:text-white">{{$getRecord()->seller->city?->name}}</td>
    </tr>

    </table>
</p>
