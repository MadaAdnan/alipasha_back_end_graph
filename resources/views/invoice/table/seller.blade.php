<p class="px-4 py-3 bg-gray-100 rounded-lg">
    <span class="font-medium">
       التاجر
    </span>

    <table class="table table-auto" style="width: 100%">
    <tr>
        <td><a href="{{\App\Filament\Resources\UserResource::getUrl('edit',['record'=>$getRecord()->seller->id])}}">{{$getRecord()->seller->name}}</a></td>
        <td><a href="https://wa.me/{{$getRecord()->seller->phone}}">{{$getRecord()->seller->phone}}</a></td>
        <td>{{$getRecord()->seller->address}} </td>
        <td>{{$getRecord()->seller->city?->name}}</td>
    </tr>

    </table>
</p>
