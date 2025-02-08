@props(['url'])
<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
@if (trim($slot) === 'Laravel')
{{-- <h3>BOD SERVICES</h3> --}}
<img src="{{ asset('images/bod-logo.png') }}" class="img-fluid logo" alt="">
@else
{{ $slot }}
@endif
</a>
</td>
</tr>
