@component('mail::message')
Hi {{ $comment->article->user->name }},<br>
A new comment was added to {{ $comment->article->title }} by 
@if($comment->user_id >0)
	{{ $comment->user->name }}
@else
	{{ $comment->name.'(guest user)' }}
@endif
. Find the comment content below:

"{{ $comment->comment }} "

Thanks,<br>
Wordify Team
@endcomponent
