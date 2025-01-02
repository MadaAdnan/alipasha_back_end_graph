

    <table class="table table-auto divide-y divide-gray-200 dark:divide-white/5" style="width: 100%">
    <tr class="divide-y divide-gray-200 dark:divide-white/5 border border-2"  style=" background-color: #4ade80">
        <td class="fi-ta-header-cell-label text-sm font-semibold text-gray-950 dark:text-white text-center" colspan="4">
            <span class="fi-ta-header-cell-label text-sm font-semibold text-gray-950 dark:text-white">التاجر</span>
        </td>
    </tr>
        <tr class="divide-y divide-gray-200 dark:divide-white/5 border border-2"  style=" background-color: #4ade80">
            <td class="fi-ta-header-cell-label text-sm font-semibold text-gray-950 dark:text-white text-center" >
              الاسم
            </td>
            <td class="fi-ta-header-cell-label text-sm font-semibold text-gray-950 dark:text-white text-center" >
                الهاتف
            </td>
            <td class="fi-ta-header-cell-label text-sm font-semibold text-gray-950 dark:text-white text-center" >
                العنوان
            </td>
            <td class="fi-ta-header-cell-label text-sm font-semibold text-gray-950 dark:text-white text-center" >
                المحافظة
            </td>
        </tr>
    <tr class="divide-y divide-gray-200 dark:divide-white/5 border border-2">
        <td><a class="fi-ta-header-cell-label text-sm font-semibold text-gray-950 dark:text-white" href="{{\App\Filament\Resources\UserResource::getUrl('edit',['record'=>$getRecord()->seller->id])}}">{{$getRecord()->seller->name}}</a></td>
        <td><a class="fi-ta-header-cell-label text-sm font-semibold text-gray-950 dark:text-white" href="https://wa.me/{{$getRecord()->seller->phone}}">{{$getRecord()->seller->phone}}</a></td>
        <td class="fi-ta-header-cell-label text-sm font-semibold text-gray-950 dark:text-white">{{$getRecord()->seller->address}} </td>
        <td class="fi-ta-header-cell-label text-sm font-semibold text-gray-950 dark:text-white">{{$getRecord()->seller->city?->name}}</td>
    </tr>
        <tr class="divide-y divide-gray-200 dark:divide-white/5 border border-2"  style=" background-color: #92400e">
            <td class="fi-ta-header-cell-label text-sm font-semibold text-gray-950 dark:text-white text-center" colspan="4">
                <span class="fi-ta-header-cell-label text-sm font-semibold text-gray-950 dark:text-white">الزبون</span>
            </td>
        </tr>
        <tr class="divide-y divide-gray-200 dark:divide-white/5 border border-2">
            <td><a class="fi-ta-header-cell-label text-sm font-semibold text-gray-950 dark:text-white" href="{{\App\Filament\Resources\UserResource::getUrl('edit',['record'=>$getRecord()->user->id])}}">{{$getRecord()->user->name}}</a></td>
            <td><a class="fi-ta-header-cell-label text-sm font-semibold text-gray-950 dark:text-white" href="https://wa.me/{{$getRecord()->phone}}">{{$getRecord()->phone}}</a></td>
            <td class="fi-ta-header-cell-label text-sm font-semibold text-gray-950 dark:text-white">{{$getRecord()->address}} </td>
            <td class="fi-ta-header-cell-label text-sm font-semibold text-gray-950 dark:text-white">{{$getRecord()->user->city?->name}}</td>
        </tr>
    </table>

