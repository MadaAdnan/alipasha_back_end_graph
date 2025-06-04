@extends('layouts.master_layouts')
@section('content')
    <div class="container">
        <div class="row justify-content-center mt-5">
            <div class="col-md-8 pt-5" dir="rtl">
                <div class="card ">
                    <div class="card-header">إنشاء شحنة</div>
                    <div class="card-body">
                        <form action="{{route('orders.store')}}" method="post">
                            @csrf
                            @method('POST')
                            {{--                            Source --}}
                            <div class="row my-1">
                                {{--                              CitySource --}}
                                <div class="col-md-6">
                                    <div class="form-group ">
                                        <label for="city-source">محافظة المرسل</label>
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
                                </div>
                                {{--                              AreaSource--}}
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="area-source">مدينة المرسل</label>
                                        <select name="areaSource" id="area-source" class="form-control">

                                        </select>
                                        @error('areaSource')
                                        <span class="text-danger">{{$message}}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="row my-1">
                                {{--                                Name Source--}}
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">اسم المرسل</label>
                                        <input name="nameSource" type="text" class="form-control">
                                        @error('nameSource')
                                        <span class="text-danger">{{$message}}</span>
                                        @enderror
                                    </div>
                                </div>
                                {{--                                Address Source --}}
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">عنوان المرسل</label>
                                        <input name="addressSource" type="text" class="form-control">
                                        @error('addressSource')
                                        <span class="text-danger">{{$message}}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            {{--                            Target --}}
                            <div class="row my-1">
                                {{--                              cityTarget --}}
                                <div class="col-md-6">
                                    <div class="form-group ">
                                        <label for="city-target">محافظة المرسل إليه</label>
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
                                {{--                              areaTarget--}}
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="area-target">مدينة المرسل إليه</label>
                                        <select name="areaTarget" id="area-target" class="form-control">

                                        </select>
                                        @error('areaTarget')
                                        <span class="text-danger">{{$message}}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="row my-1">
                                {{--                                nameTarget--}}
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">اسم المرسل إليه</label>
                                        <input name="nameTarget" type="text" class="form-control">
                                        @error('nameTarget')
                                        <span class="text-danger">{{$message}}</span>
                                        @enderror
                                    </div>
                                </div>
                                {{--                                addressTarget --}}
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">عنوان المرسل إليه</label>
                                        <input name="addressTarget" type="text" class="form-control">
                                        @error('addressTarget')
                                        <span class="text-danger">{{$message}}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row my-1">
                                {{--                                phoneTarget--}}
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">هاتف المرسل إليه</label>
                                        <input name="phoneTarget" type="text" class="form-control">
                                        @error('phoneTarget')
                                        <span class="text-danger">{{$message}}</span>
                                        @enderror
                                    </div>
                                </div>
                                {{--                                Weight  --}}
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">وزن الحمولة</label>
                                        <input name="weight" id="weight" type="text" class="form-control">
                                        @error('weight')
                                        <span class="text-danger">{{$message}}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            {{--                            Dimentions --}}
                            <div class="row my-1">
                                {{--                                height--}}
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">الإرتفاع</label>
                                        <input name="height" id="height" type="text" class="form-control">
                                        @error('height')
                                        <span class="text-danger">{{$message}}</span>
                                        @enderror
                                    </div>
                                </div>
                                {{--                                width  --}}
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">العرض</label>
                                        <input name="width" id="width" type="text" class="form-control">
                                        @error('width')
                                        <span class="text-danger">{{$message}}</span>
                                        @enderror
                                    </div>
                                </div>
                                {{-- Length --}}
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">الطول</label>
                                        <input name="length" id="length" type="text" class="form-control">
                                        @error('width')
                                        <span class="text-danger">{{$message}}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="">ملاحظات</label>
                                <textarea name="note" id="" cols="30" rows="3" class="form-control"></textarea>
                            </div>
                            <div class="row mt-2">
                                <div class="col-6 border border-1 p-2 text-center">
                                    <button class="btn btn-sm btn-primary w-100" type="button" onclick="calculate()">
                                        أحسب
                                        التكلفة
                                    </button>
                                </div>
                                <div class="col-6 border border-1 p-2 text-center">
                                    <span class="text-danger" id="message"></span>
                                </div>
                                <div class="col-12 mt-2 text-center">
                                    <button class="btn btn-sm btn-danger w-50" type="submit">إشحن الحمولة</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            {{--EndForm--}}
            @forelse($orders as $order)
                <div class="col-md-8 " dir="rtl">
                    <div class="card " style="margin-top: 100px">
                        <div class="card-header">
                            <h3>رقم الطلب : {{$order->id}}</h3>
                        </div>
                        <div class="card-body">

                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <tr>
                                        <th>اسم المرسل</th>
                                        <td>{{$order->sender_name}}</td>
                                    </tr>
                                    <tr>
                                        <th>عنوان المرسل</th>
                                        <td>{{$order->sender_address}}</td>
                                    </tr>
                                    <tr>
                                        <th>هاتف المرسل</th>
                                        <td>{{$order->sender_phone}}</td>
                                    </tr>
                                    <tr>
                                        <th>اسم المرسل إليه</th>
                                        <td>{{$order->receive_name}}</td>
                                    </tr>
                                    <tr>
                                        <th>عنوان المرسل إليه</th>
                                        <td>{{$order->receive_address}}</td>
                                    </tr>
                                    <tr>
                                        <th>هاتف المرسل إليه</th>
                                        <td>{{$order->receive_phone}}</td>
                                    </tr>
                                    <tr>
                                        <th>وزن الحمولة</th>
                                        <td>{{$order->weight}}</td>
                                    </tr>
                                    <tr>
                                        <th>حجم الحمولة</th>
                                        <td>{{$order->size}}</td>
                                    </tr>
                                    <tr>
                                        <th>حالة الشحنة</th>
                                        <td>{{\App\Enums\OrderStatusEnum::tryFrom($order->status)->getLabel()}}</td>
                                    </tr>
                                    <tr>
                                        <th>أجور الشحن</th>
                                        <td>{{$order->price}}</td>
                                    </tr>
                                    <tr>
                                        <th>تاريخ الطلب</th>
                                        <td>{{$order->created_at?->format('a h:i | Y-m-d')}}</td>
                                    </tr>
                                    <tr>
                                        <th>ملاحظات</th>
                                        <td>{{$order->note}}</td>
                                    </tr>

                                </table>
                            </div>
                        </div>

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
    <script>
        const cities = @json($cities);
        const pricing = @json($pricing);
        // pricingWeight.sort((a, b) => a.weight - b.weight);
        const pricingSize = @json($pricing);
        //  pricingSize.sort((a, b) => a.size - b.size);
        //  console.log(pricingWeight,pricingSize)
        var areaSource;
        var areaTarget;

        document.getElementById('city-source').addEventListener('change', function () {
            const selectedCityId = this.value;
            const districtSelect = document.getElementById('area-source');

            // تفريغ القائمة القديمة
            districtSelect.innerHTML = '<option value="">اختر مدينة</option>';

            // البحث عن المدينة المختارة
            const selectedCity = cities.find(city => city.id == selectedCityId);

            if (selectedCity && selectedCity.children.length > 0) {
                selectedCity.children.forEach(function (district) {
                    const option = document.createElement('option');
                    option.value = district.id;
                    option.textContent = district.name;
                    districtSelect.appendChild(option);
                });
            }
        });
        document.getElementById('city-target').addEventListener('change', function () {
            const selectedCityId = this.value;
            const districtSelect = document.getElementById('area-target');

            // تفريغ القائمة القديمة
            districtSelect.innerHTML = '<option value="">اختر مدينة</option>';

            // البحث عن المدينة المختارة
            const selectedCity = cities.find(city => city.id == selectedCityId);

            if (selectedCity && selectedCity.children.length > 0) {
                selectedCity.children.forEach(function (district) {
                    const option = document.createElement('option');
                    option.value = district.id;
                    option.textContent = district.name;
                    districtSelect.appendChild(option);
                });
            }
        });

        function calculate() {
            var weight = document.getElementById('weight').value
            var height = document.getElementById('height').value
            var width = document.getElementById('width').value
            var length = document.getElementById('length').value
            var message = document.getElementById('message')
            // Find City Source Selected
            var citySourceSelected = cities.find(city => city.id == document.getElementById('city-source').value)
            if (citySourceSelected == undefined) {
                message.innerText = "يرجى تحديد محافظة المرسل"
                return
            }
            var areaSourceSelected = citySourceSelected.children.find(area => area.id == document.getElementById('area-source').value)
            if (areaSourceSelected == undefined) {
                message.innerText = "يرجى تحديد مدينة المرسل"
                return
            }
            // Find City Target Selected
            var cityTargetSelected = cities.find(city => city.id == document.getElementById('city-target').value)
            if (cityTargetSelected == undefined) {
                message.innerText = "يرجى تحديد محافظة المرسل إليه"
                return
            }
            var areaTargetSelected = cityTargetSelected.children.find(area => area.id == document.getElementById('area-target').value)

            if (areaTargetSelected == undefined) {
                message.innerText = "يرجى تحديد مدينة المرسل إليه"
                return
            }
            if (width == undefined || width == '' || parseFloat(width) <= 0) {
                message.innerText = "يرجى إدخال العرض  بشكل صحيح"
                return;
            }
            if (height == undefined || height == '' || parseFloat(height) <= 0) {
                message.innerText = "يرجى إدخال الإرتفاع  بشكل صحيح"
                return;
            }
            if (length == undefined || length == '' || parseFloat(length) <= 0) {
                message.innerText = "يرجى إدخال الطول  بشكل صحيح"
                return;
            }
            if (weight == undefined || weight == '' || parseFloat(weight) <= 0) {
                message.innerText = "يرجى إدخال الوزن  بشكل صحيح"
                return;
            }
            if (areaTargetSelected.is_delivery == 0 || areaSourceSelected.is_delivery == 0 || areaTargetSelected.level == undefined || areaSourceSelected.level == undefined) {
                message.innerText = "الشحن غير متاح بين هذه المدن"
                return;
            }
            const steps = (parseInt(areaSourceSelected.level) + parseInt(areaTargetSelected.level)) - 1;
            const priceWight = parseFloat(pricing.find(p => p.weight >= weight)?.internal_price ?? 0)
            const sizeTotal = ((width * 0.01) * (height * 0.01) * (length * 0.01))
            const priceSize = parseFloat(pricing.find(p => p.size >= sizeTotal)?.internal_price ?? 0)
            if (priceWight == undefined && priceSize == undefined) {
                message.innerText = "الحجم المدخل غير مسموح به يرجى التواصل مع الإدارة"
                return;
            }

            var far = priceWight > priceSize ? priceWight : priceSize

            totalFar = far + ((far / 3) * steps)

            message.innerHTML = `<span class="h5">أجور الشحن :</span><span class="h3">${totalFar} $</span>
`


        }
    </script>

@endsection
