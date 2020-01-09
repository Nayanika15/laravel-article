@component('mail::message')
Hi Admin,<br>
{{ ucFirst($userDetail->name) }} has reset password successfully.<br>
Thanks,<br>
Team Wordify
@endcomponent