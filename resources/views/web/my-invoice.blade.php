@extends('layouts.master_layouts')
@section('content')
    <div class="container" dir="rtl">
        <div class="row justify-content-center">
            @forelse($invoices as $invoice)
                <div class="col-8 ">
                    <div class="card " style="margin-top: 100px">
                        <div class="card-title">
                            <span>رقم الطلب : {{$invoice->id}}</span>
                        </div>
                        <div class="card-body">
                            <div>
                                <span>البائع : {{$invoice->seller?->name}} <a href="https://wa.me/{{$invoice->seller?->phone}}"><i class="bi bi-whatsapp"></i></a></span>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <tr>
                                        <th>المنتج</th>
                                        <th>سعر الوحدة</th>
                                        <th>الكمية</th>
                                        <th>الإجمالي</th>
                                    </tr>
                                    @foreach($invoice->items as $item)
                                        <tr>
                                            <td>{{$item->product?->name}}</td>
                                            <td>{{$item->price}}</td>
                                            <td>{{$item->qty}}</td>
                                            <td>{{$item->total}}</td>
                                        </tr>
                                    @endforeach

                                </table>
                            </div>
                        </div>

                    </div>

                </div>
            @empty
                <h3 class="alert alert-info" style="margin-top: 100px">لا يوجد طلبات</h3>
            @endforelse

                <div class="col-md-12">
                    <div class="d-flex justify-content-between">
                        @if($invoices->hasMorePages())
                            <a class="btn btn-sm btn-secondary"
                               href="{{$invoices->withQueryString()->nextPageUrl()}}">التالي</a>
                        @endif
                            @if($invoices->currentPage()>1)
                        <a class="btn btn-sm btn-secondary" href="{{$invoices->withQueryString()->previousPageUrl()}}">السابق</a>
                                @endif
                    </div>
                </div>
        </div>
    </div>
@endsection
