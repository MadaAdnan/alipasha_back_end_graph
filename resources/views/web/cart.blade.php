@extends('layouts.master_layouts')
@section('content')
    <div class="container">
        <div class="row justify-content-center" style="margin-top: 100px">
            <div class="table-responsive">
                <table class="table table-striped" dir="rtl">
                    <thead>
                    <tr>
                        <th class="text-center">معرف المنتج</th>
                        <th class="text-center">صورة المنتج</th>
                        <th class="text-center">اسم المنتج</th>

                        <th class="text-center">السعر</th>
                        <th class="text-center">الكمية</th>
                        <th class="text-center">الإجمالي</th>
                    </tr>
                    </thead>
                    <tbody>
                    @php
                        $result=0;
                    @endphp
                    @foreach($items as $item)



                        <tr>
                            <td class="text-center">{{$item->product?->id}}</td>
                            <td class="text-center"><img style="width: 60px;aspect-ratio: 1/1"
                                     src="{{$item->product?->hasMedia('images')?$item->product?->getImage('images'):$item->product?->getImage()}}"
                                     alt=""></td>
                            <td class="text-center">{{$item->product?->name}}</td>

                            <td class="text-center">{{$item->product?->getPrice()}} $</td>
                            <td class="text-center">{{$item->qty}}</td>
                            @php
                                $total=$item->product?->getPrice() * $item->qty;
                        $result+=$total;
                            @endphp
                            <td class="text-center">{{$total}}</td>
                        </tr>
                    @endforeach

                    </tbody>
                    <tfoot>
                    <tr>
                        <th colspan="2" class="text-center bg-info-subtle">الإجمالي</th>
                        <th colspan="4" class="text-center bg-info-subtle">{{$result}} $</th>
                    </tr>
                    </tfoot>
                </table>
            </div>




        </div>
    </div>
@endsection
