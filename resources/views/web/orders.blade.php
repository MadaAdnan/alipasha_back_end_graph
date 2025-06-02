@extends('layouts.master_layouts')
@section('content')
    <div class="container">
        <div class="row justify-content-center mt-5">
            <div class="col-md-8 pt-5" dir="rtl">
              <div class="card ">
                  <div class="card-header">إنشاء شحنة</div>
                  <div class="card-body">
                      <form action="">
                          <div class="row">
                              <div class="form-group">
                                  <label for="city-source">مدينة المرسل</label>
                                  <select name="citySource" id="city-source" class="form-control">
                                      <option value=""></option>
                                      @foreach($cities as $city)
                                          <option value="{{$city->id}}">{{$city->name}}</option>
                                      @endforeach
                                  </select>
                                  @error('citySource')
                                  <span class="text-danger">{{$message}}</span>
                                  @enderror
                              </div>
                              <div class="form-group">
                                  <label for="city-target">مدينة المرسل إليه</label>
                                  <select name="cityTarget" id="city-target" class="form-control">
                                      <option value=""></option>
                                      @foreach($cities as $city)
                                          <option value="{{$city->id}}">{{$city->name}}</option>
                                      @endforeach
                                  </select>
                                  @error('cityTarget')
                                  <span class="text-danger">{{$message}}</span>
                                  @enderror
                              </div>
                          </div>
                      </form>
                  </div>
              </div>
            </div>
            @forelse($orders as $order)
                <div class="col-8 ">
                    <div class="card " style="margin-top: 100px">
                        <div class="card-title">
                            <span>رقم الطلب : {{$order->id}}</span>
                        </div>
                        <div class="card-body">
                            <div>
                                <span>{{$order->user?->name}} <a href="https://wa.me/{{$order->user?->phone}}"><i class="bi bi-whatsapp"></i></a></span>
                            </div>
                        {{--    <div class="table-responsive">
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
                            </div>--}}
                        </div>
                       {{-- <div class="card-footer">
                            <div class="d-flex">
                                <form action="{{route('invoices.update',$invoice->id)}}" method="post">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="status" value="{{\App\Enums\OrderStatusEnum::AGREE->value}}">
                                    <button class="btn-sm btn-success">قبول الطلب</button>
                                </form>
                                <form action="{{route('invoices.update',$invoice->id)}}" method="post">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="status" value="{{\App\Enums\OrderStatusEnum::CANCELED->value}}">
                                    <button class="btn-sm btn-danger">رفض الطلب</button>
                                </form>
                            </div>
                        </div>--}}
                    </div>

                </div>
            @empty
                <h3 class="alert alert-info" style="margin-top: 100px">لا يوجد طلبات</h3>
            @endforelse
<div class="col-12">
    <div class="d-flex justify-content-between">
        @if($orders->hasMorePages())
            <a class="btn btn-sm btn-secondary"
               href="{{$orders->withQueryString()->nextPageUrl()}}">التالي</a>
        @endif
        @if($orders->currentPage()>1)
            <a class="btn btn-sm btn-secondary" href="{{$orders->withQueryString()->previousPageUrl()}}">السابق</a>
        @endif
    </div>
</div>
        </div>
    </div>
@endsection

@section('js')

@endsection
