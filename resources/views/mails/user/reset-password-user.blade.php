@component('mail::message')
Hi {{ ucFirst($mailData['user']->name) }},<br>
You have successfully reset your password.Your new password is <b>{{ $mailData['password'] }}</b>. Please login to your account to submit new articles.<br>
Thanks,<br>
Team Wordify
@endcomponent
