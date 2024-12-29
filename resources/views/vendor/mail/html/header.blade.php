@props(['url'])
<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
@if (trim($slot) === 'Laravel')
<img src="{{\App\Models\Setting::first()?->getFirstMediaUrl('logo','webp')}}" class="logo" alt="Ali Pasha">
@else
{{ $slot }}
@endif
</a>
</td>
</tr>
