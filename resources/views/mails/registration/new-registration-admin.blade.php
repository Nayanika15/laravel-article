@component('mail::message')
Hi Admin,<br>
A new user has registered successfully to wordify.Find the details of the user below:<br>
	Name:{{ $userDetail->name }}<br>
	Email:{{ $userDetail->email }}<br>
Thanks,<br>
Team Wordify
@endcomponent
