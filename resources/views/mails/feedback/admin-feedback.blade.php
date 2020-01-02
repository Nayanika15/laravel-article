@component('mail::message')
Hi Admin,<br>
<p>
A new user has submitted some feedback.Find the details of the user below:
	<br>Name:&nbsp;{{ $feedback->name }}<br>
	Email:&nbsp;{{ $feedback->email }}<br>
	Message:&nbsp;{{ $feedback->message}}<br>
</p>
Thanks,<br>
Team Wordify
@endcomponent
