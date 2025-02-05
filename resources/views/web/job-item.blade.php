@extends('layouts.master_layouts')

@section('content')
    <div class="col-12 col-xl-9" style="margin-top: 10px">
        <div class="containter" style="background-color: #fff; padding: 16px; border-radius: 16px;">
            <h1  class="title mb-4" style="text-align: right;"> مناقصة</h1>
            <div class="card mb-3" style="width: 100%;">
                <div class="row g-0">
                    <div class="col-md-8">
                        <div class="card-body" style="position: relative;" dir="rtl">
                            <div style="display: flex; align-items: center; gap: 8px;">
                                <a href="./profile.html">
                                    <img class="rounded-circle" width="75" src="{{$job->user?->getImage()}}" alt="" />
                                </a>
                                <div style="display: flex; gap: 4px; flex-direction: column">
                                    <p class="title" style="text-align: right"> {{$job->user?->seller_name}}
                                    @if($job->user?->is_verified)
                                            <i class="bi bi-patch-check" style="color: blue; font-size: 16px;"></i>
                                        @endif
                                    </p>
                                </div>

                            </div>
                            <p class="card-text">{!! $job->info !!}</p>
                            <p class="card-text">بداية التقديم : {{$job->start_date?->format('Y-m-d')}} - نهاية التقديم {{$job->end_date?->format('Y-m-d')}}</p>
                            <p class="card-text" > <i class="bi bi-eye" style="font-size: 14px; color: red;"></i> {{$job->views_count}} </p>
                            <p class="card-text" > <i class="bi bi-copy" style="font-size: 14px; color: red;"></i> {{$job->code}} </p>
                            <p class="card-text" > <i class="bi bi-envelope" style="font-size: 14px; color: red;"></i> {{$job->email}}</p>
                            <p class="card-text" > <i class="bi bi-telephone" style="font-size: 14px; color: red;"></i>  {{$job->phone}}</p>
                            <p class="card-text"> <i class="bi bi-geo-alt" style="font-size: 14px; color: red;"></i> {{$job->city?->name}} - {{$job->category?->name}} - {{$job->sub1?->name}}</p>
                            {{--<a href="https://www.linkedin.com/in/obada-kahlous">
                                <p class="card-text"> stars </p>
                            </a>--}}
                            <form action="">
                                <input type="hidden" name="free" value="123">
                                <button type="submit" class="btn" style="width: 100%; background-color: #e30613; color: #fff; margin: 20px 0px;">إبلاغ عن المناقصة</button>
                            </form>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <img src="{{$job->user?->getImage()}}" class="img-fluid w-100 rounded-start" alt="...">
                    </div>
                </div>
            </div>
            <p class="title mb-4" style="text-align: center; margin: 20px 0px; font-size: 20px;"> الملفات المرفقة </p>
            <div class="files" style="display: flex; gap: 10px; flex-wrap: wrap;">
                <div style="width: 300px; height: auto;">
                    @foreach($job->getMedia('attach') as $media)
                        <a href="{{$media->getUrl()}}">مرفق {{$loop->iteration}}</a>
                    @endforeach

                </div>

            </div>
        </div>
    </div>
@endsection
