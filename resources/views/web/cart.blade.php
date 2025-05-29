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
                        <th class="text-center">حالة الشحن</th>
                        <th class="text-center">#</th>
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
                                   if($item->product?->is_delivery){


                               $result+=$total;
                                    }
                            @endphp
                            <td class="text-center">{{$total}}</td>
                            <td class="text-center">
                                @if($item->product?->is_delivery==false)
                                    <span class="badge text-bg-danger">غير متاح</span>
                                @else
                                    <span class="badge text-bg-success"> متاح</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <form action="{{route('carts.destroy',$item->id)}}" method="post">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm text-white"><i
                                            class="bi bi-trash-fill"></i></button>
                                </form>
                            </td>
                        </tr>
                    @endforeach

                    </tbody>
                    <tfoot>
                    <tr>
                        <th colspan="2" class="text-center bg-info-subtle">أجور شحن</th>
                        <th colspan="2" class="text-center bg-info-subtle">{{$shipping}} $</th>
                        <th colspan="2" class="text-center bg-info-subtle">إجمالي القيمة</th>
                        <th colspan="2" class="text-center bg-info-subtle">{{$result}} $</th>

                    </tr>
                    <tr>
                        <th colspan="2" class="text-center bg-danger-subtle">الإجمالي</th>
                        <th colspan="2" class="text-center bg-danger-subtle">{{$result+$shipping}} $</th>
                        <th colspan="4" class="text-center ">
                            @if($shipping>0 &&
(  auth()->user()->area?->is_delivery==true) && $user->area?->is_delivery==true)
                                <form action="{{route('carts.update',$user->id)}}" method="post">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-danger btn-sm text-white">اطلب الآن</button>

                                </form>
                                @elseif($user->area_id==null)
                                <span>يرجى إكمال ملفك الشخصي كي تتمكن من الشحن</span>
                            @else
                               <span > الشحن غير متاح حاليا لهذا المنتج</span>
                            @endif
                        </th>
                    </tr>
                    </tfoot>
                </table>
            </div>


        </div>
    </div>
@endsection
