@extends('theme2.layouts.master')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                @foreach($notifications as $notification)
                    <div class="filter-container">
                        <div class="filter-header">
                            <i class="fas fa-comments"></i>
                            <span>{{$notification->data['title']}}</span>
                        </div>
                        <p>{{$notification->data['body']}}</p>
                        <div class="d-flex justify-content-end">
                            <span>{{$notification->created_at->diffForHumans()}}</span>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="col-md-12">
                <x-components.paginator-component :paginator="$notifications"/>
            </div>
        </div>
@endsection
