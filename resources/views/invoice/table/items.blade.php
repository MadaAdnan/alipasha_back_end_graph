<p class="px-4 py-3 bg-gray-100 rounded-lg">
    <span class="font-medium">
        Email address:
    </span>

    <table>
       @foreach($getRecord()->items as $item)
           <tr>
               <td>{{$item->product->name}}</td>
           </tr>

       @endforeach
    </table>
</p>
