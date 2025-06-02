@extends('layouts.master_layouts')
@section('content')
    <div class="container">
        <div class="row justify-content-center mt-5">
            <div class="col-md-8 mt-5" dir="rtl">
            @forelse($notifications as $notification)
<div class="card my-2 p-2">
    <div class="card-title"><h4 class="fw-bold">{{$notification->data['title']}}</h4></div>
    <div class="card-body">{{$notification->data['body']}}</div>
    <div class="card-footer">{{$notification->created_at->format('h:i a | Y-m-d')}}</div>
</div>
            @empty
                <h3 class="alert alert-info" style="margin-top: 100px">لا يوجد إشعارات</h3>
            @endforelse
        </div>
                <div class="col-md-8">
                    <div class="d-flex justify-content-between">
                        @if($notifications->hasMorePages())
                            <a class="btn btn-sm btn-secondary"
                               href="{{$notifications->withQueryString()->nextPageUrl()}}">التالي</a>
                        @endif
                        @if($notifications->currentPage()>1)
                            <a class="btn btn-sm btn-secondary" href="{{$notifications->withQueryString()->previousPageUrl()}}">السابق</a>
                        @endif
                    </div>
                </div>
        </div>
    </div>
@endsection
