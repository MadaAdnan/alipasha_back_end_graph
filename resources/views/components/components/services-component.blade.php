@props([
    'services'=>[]
])
<div class="">

    @foreach($services as $service)
        <div class="service-card d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center gap-3">
                <div class="icon-box bg-red">
                    <i class="fa-solid fa-briefcase"></i>
                </div>

                <div>
                    <h6 class="mb-1 fw-bold">{{$service->name}}</h6>
                    <p class="mb-1 text-muted small">
                        {{$service->category?->name}}
                    </p>
                    <span class="badge bg-light text-dark">
                    <i class="fa-solid fa-location-dot"></i> {{$service->city?->name}}
                </span>
                </div>
            </div>

            <a href="{{route('services.show',$service->id)}}" class=" rounded px-2 py-1 bg-gradient text-white btn-sm">
                <span class="fs-7 text-white">استعراض</span> <i class="fa-solid fa-arrow-left fs-7"></i>
            </a>
        </div>

    @endforeach

</div>
<style>


    .service-card {
        background: #fff;
        border-radius: 12px;
        padding: 15px 20px;
        margin-bottom: 12px;
        box-shadow: 0 4px 12px rgba(0,0,0,.05);
        transition: 0.2s ease;
    }

    .service-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(0,0,0,.08);
    }

    .icon-box {
        width: 45px;
        height: 45px;

        color: #fff;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
    }

    .btn-success {
        border-radius: 20px;
        padding: 6px 14px;
    }

</style>
