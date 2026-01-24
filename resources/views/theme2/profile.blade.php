@extends('theme2.layouts.master')
@section('content')

    <div class="container">
        <div class="row">
            <div class="col-md-3">
                <x-components.change-password-component/>
            </div>
            <div class="col-md-9">
                <x-components.profile-component/>
            </div>
        </div>
    </div>
@endsection
