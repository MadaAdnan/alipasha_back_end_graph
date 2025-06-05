@extends('layouts.master_layouts')
@section('title')
     الرصيد
@endsection
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12 ">
                <div class="table-responsive" style="margin-top: 100px">
                    <table class="table table-striped" dir="rtl">
                        <thead>
                        <tr>
                            <th>سحب</th>
                            <th>إيداع</th>
                            <th>الرصيد</th>
                            <th>البيان</th>
                            <th>
                                <a href="{{route('balances.index',['sort'=>request()->get('sort')=='desc'?'asc':'desc'])}}">التاريخ
                                    @if(request()->get('sort')=='desc')
                                        <i class="bi bi-sort-down-alt"></i>

                                    @else
                                        <i class="bi bi-sort-up"></i>
                                    @endif
                                </a></th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($balances as $balance)
                            <tr>
                                <td>{{$balance->debit}}</td>
                                <td>{{$balance->credit}}</td>
                                <td>{{$balance->total}}</td>
                                <td>{{$balance->info}}</td>
                                <td>{{$balance->created_at->format('Y-m-d H:i')}}</td>

                            </tr>
                        @endforeach

                        </tbody>
                        <tfoot>
                        @if($balances->hasMorePages())
                            <a class="btn btn-sm btn-secondary"
                               href="{{$balances->withQueryString()->nextPageUrl()}}">التالي</a>
                        @endif
                        @if($balances->currentPage()>1)
                            <a class="btn btn-sm btn-secondary"
                               href="{{$balances->withQueryString()->previousPageUrl()}}">السابق</a>
                        @endif
                        </tfoot>
                    </table>

                    <div class="d-flex justify-content-between">

                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
