<p class="px-4 py-3 bg-gray-100 rounded-lg">
    <span class="font-medium">
       الزبون
    </span>

    <table class="table table-auto" style="width: 100%">
    <tr>
        <td><a href="{{\App\Filament\Resources\UserResource::getUrl('edit',['record'=>$getRecord()->user->id])}}">{{$getRecord()->user->name}}</a></td>
        <td><a href="https://wa.me/{{$getRecord()->phone}}">{{$getRecord()->phone}}</a></td>
        <td>{{$getRecord()->address}} </td>
        <td>{{$getRecord()->user->city?->name}}</td>
    </tr>

    </table>
</p>
