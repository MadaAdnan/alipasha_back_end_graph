@props(['next','back','hasMore','current'])
<div class="col-12 my-2">
   <div class="d-flex justify-content-between align-items-center">
       @if($hasMore)
       <a class="btn btn-sm bg-white" href="{{$next}}">التالي</a>
           @endif
           @if((int)$current > 1)
       <a class="btn btn-sm bg-white" href="{{$back}}">السابق</a>
           @endif
   </div>
</div>
