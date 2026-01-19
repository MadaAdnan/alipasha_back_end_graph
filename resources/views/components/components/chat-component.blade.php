@props([
    'community'=>null
])
<div>

    <div class="categories-header">
        @if($community)
        <i class="fas fa-list"></i>
        <span>{{$community->name}}</span>
        @else
            <i class="fas fa-list"></i>
            <span>لا يوجد محادثات لعرضها</span>
        @endif
    </div>







</div>
