<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
@if (trim($slot) === 'Laravel')
<img src="https://laravel.com/img/notification-logo.png" class="logo" alt="Laravel Logo">
@else
<img src="{{ $url }}/img/svg/logo-primary.svg" class="logo" alt="{{ $slot }}">
@endif
</a>
</td>
</tr>
