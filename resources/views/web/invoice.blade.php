@extends('layouts.master_layouts')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            @foreach($invoices as $invoice)
                <div class="col-8 ">
                    <div class="card">
                        <div class="card-title">
                            <span>رقم الطلب : {{$invoice->id}}</span>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped">
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
            @endforeach

        </div>
    </div>
@endsection
