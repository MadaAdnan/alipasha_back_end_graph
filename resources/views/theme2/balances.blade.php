@extends('theme2.layouts.master')
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

                    </table>

                    <div class="d-flex justify-content-center w-100">
<x-components.paginator-component :paginator="$balances"/>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
