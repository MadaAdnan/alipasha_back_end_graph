@extends('layouts.master_layouts')
@section('content')
    <div class="container-fluid" style="margin-top: 70px">
        <div class="row justify-content-center">

            <div class="col-md-8">
                @foreach($messages as $message)
                <div class="card card-body">
                    <div class="d-flex">

                        <span>{{$message->body}}</span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection
