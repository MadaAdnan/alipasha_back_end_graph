<p class="px-4 py-3 bg-gray-100 rounded-lg">
    <span class="font-medium">
       المنتجات
    </span>

    <table>
       @foreach($getRecord()->items as $item)
           <tr>
               <td>{{$item->product->name}}</td>
               <td>{{$item->qty}}</td>
               <td>{{$item->product?->is_delivery}}</td>
           </tr>

       @endforeach
    </table>
</p>
