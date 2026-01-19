@props([
    'community'=>null
])
<div>
    @if($community)
{{$community->name}}
        @endif
</div>
