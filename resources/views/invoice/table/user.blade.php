<p class="divide-y divide-gray-200 dark:divide-white/5">
    <span class="font-medium">
       الزبون
    </span>

    <table class="table table-auto" style="width: 100%">
    <tr>
        <td><a class="fi-ta-header-cell-label text-sm font-semibold text-gray-950 dark:text-white" href="{{\App\Filament\Resources\UserResource::getUrl('edit',['record'=>$getRecord()->user->id])}}">{{$getRecord()->user->name}}</a></td>
        <td><a class="fi-ta-header-cell-label text-sm font-semibold text-gray-950 dark:text-white" href="https://wa.me/{{$getRecord()->phone}}">{{$getRecord()->phone}}</a></td>
        <td class="fi-ta-header-cell-label text-sm font-semibold text-gray-950 dark:text-white">{{$getRecord()->address}} </td>
        <td class="fi-ta-header-cell-label text-sm font-semibold text-gray-950 dark:text-white">{{$getRecord()->user->city?->name}}</td>
    </tr>

    </table>
</p>
