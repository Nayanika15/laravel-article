@component('mail::message')
Hi Admin,<br>
A new article is added by {{ $article->user->name }}.Please find the details below :<br>
<p>
	Title : {{ $article->title }}<br>
	Categories : 
		<ul>@foreach($article->categories as $category)
			<li>{{ $category->name }}</li>
			@endforeach
		</ul><br>
	Content: {!! $article->details !!}
</p>
Thanks,<br>
Wordify Team
@endcomponent
