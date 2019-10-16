@component('mail::message')
Hi {{$mailData['user']->name}},<br>
You have successfully registered to wordify.Your password is "{{ $mailData['password'] }}". Please login to your account to submit new articles.<br>
Thanks,<br>
Team Wordify
@endcomponent
