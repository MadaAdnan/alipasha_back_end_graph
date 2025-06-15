@extends('layouts.master_layouts')
@section('title')
    {{$tender->name ?? Str::words($tender->expert,2)}}
@endsection
@section('content')
    <div class="col-12 col-md-8 offset-md-2" style="margin-top: 70px">
        <div class="containter" style="background-color: #fff; padding: 16px; border-radius: 16px;">
            <h1  class="title mb-4" style="text-align: right;"> مناقصة</h1>
            <div class="card mb-3" style="width: 100%;">
                <div class="row g-0">
                    <div class="col-md-8">
                        <div class="card-body" style="position: relative;" dir="rtl">
                            <div style="display: flex; align-items: center; gap: 8px;">
                                <a href="{{route('seller.profile',['id'=>$tender->user?->id])}}">
                                    <img class="rounded-circle" width="75" src="{{$tender->user?->getImage()}}" alt="" />
                                </a>
                                <div style="display: flex; gap: 4px; flex-direction: column">
                                    <p class="title" style="text-align: right"> {{$tender->user?->seller_name}}
                                    @if($tender->user?->is_verified)
                                            <i class="bi bi-patch-check" style="color: blue; font-size: 16px;"></i>
                                        @endif
                                    </p>
                                </div>

                            </div>
                            <p class="card-text">{!! $tender->info !!}</p>
                            <p class="card-text">بداية التقديم : {{$tender->start_date?->format('Y-m-d')}} - نهاية التقديم {{$tender->end_date?->format('Y-m-d')}}</p>
                            <p class="card-text" > <i class="bi bi-eye" style="font-size: 14px; color: red;"></i> {{$tender->views_count}} </p>
                            <p class="card-text" > <i class="bi bi-copy" style="font-size: 14px; color: red;"></i> {{$tender->code}} </p>
                            @if($tender->email!='')
                            <p class="card-text" > <i class="bi bi-envelope" style="font-size: 14px; color: red;"></i> {{$tender->email}}</p>
                            @endif
                            @if($tender->phone!='')
                            <p class="card-text" > <i class="bi bi-telephone" style="font-size: 14px; color: red;"></i>  {{$tender->phone}}</p>
                                @endif
                            <p class="card-text"> <i class="bi bi-geo-alt" style="font-size: 14px; color: red;"></i> {{$tender->city?->name}} - {{$tender->category?->name}} - {{$tender->sub1?->name}}</p>
                            @if($tender->url!='')
                            <p class="card-text"> <i class="bi bi-link-45deg" style="font-size: 14px; color: red;"></i>
                                <a target="_blank" href="{{$tender->url}}">{{$tender->url}}</a></p>
                                    @endif
                            @php
                                $sellerId=\App\Models\Setting::first()->support_id;
                            @endphp
                            <form action="{{route('communities.store')}}" method="post">
                                @csrf
                                @method('POST')

                                <input type="hidden" name="sellerId" value="{{$sellerId}}">
                                <button type="submit" class="btn" style="width: 100%; background-color: #e30613; color: #fff; margin: 20px 0px;">إبلاغ عن المناقصة</button>
                            </form>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <img src="{{$tender->user?->getImage()}}" class="img-fluid w-100 rounded-start" alt="...">
                    </div>
                </div>
            </div>
            @if($tender->hasMedia('attach'))
            <p class="title mb-4" style="text-align: center; margin: 20px 0px; font-size: 20px;"> الملفات المرفقة </p>
            <div class="files" style="display: flex; gap: 10px; flex-wrap: wrap;">
                <div style="width: 300px; height: auto;">
                    @foreach($tender->getMedia('attach') as $media)
                        <a href="{{$media->getUrl()}}">مرفق {{$loop->iteration}}</a>
                    @endforeach

                </div>

            </div>
                @endif
        </div>
    </div>
@endsection
