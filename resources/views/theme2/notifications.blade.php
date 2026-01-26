@extends('theme2.layouts.master')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                @foreach($notifications as $notification)

                @endforeach
            </div>
            <div class="col-md-12">
                <x-components.paginator-component :paginator="$notifications"/>
            </div>
        </div>
@endsection
