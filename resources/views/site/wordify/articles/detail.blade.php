@extends('layouts.wordify')
@section('title')
  {{ ' Article-' . env('SITE_TITLE') }}
@endsection

@section('content')
  @php($article = $data['article'])
  @php($comments = $data['comments'])

  <section class="site-section py-lg">
    <div class="container">        
      <div class="row blog-entries element-animate">
        @if(!empty($article))
          <div class="col-md-12 col-lg-8 main-content">
            @include('common.message')
            @if($article->getMedia('articles')->count() > 0)
              <img src="{{ ($article->getMedia('articles')->count() > 0)?$article->getFirstMedia('articles')->getUrl('detail'): asset('images/img_2.jpg') }}" alt="Image" class="img-fluid mb-5">
            @endif

            <div class="post-meta">
              <span class="author mr-2">
                <img src="{{asset('images/person_1.jpg')}}" alt="{{ ($article->user()->first()->name) ?? $article->user()->first()->name ?? 'guest' }}" class="mr-2">{{ ($article->user()->first()->name) ?? $article->user()->first()->name ?? 'guest' }}
              </span>&bullet;
              <span class="mr-2">
                {{ date('d-M-Y',strtotime($article->created_at)) }} 
              </span> &bullet;
              <span class="ml-2">
                <span class="fa fa-comments"></span>
                {{ count($comments) }}
              </span>
            </div>
            <h1 class="mb-4">{{ $article->title }}</h1>
              @foreach($article->categories()->get() as $category)
                <a class="category mb-5" href="{{ url($category->permalink) }}">{!! $category->name !!}</a>
              @endforeach
            <div class="post-content-body">{!! $article->details !!}</div>
            
            <div class="pt-5">
              @if($comments->count() >0 )
                <h3 class="mb-5">{{ $comments->count() }} comments</h3>
                <ul class="comment-list">
                  @foreach($comments as $comment)
                  <li class="comment">
                    <div class="comment-body">
                      <h3>{{ (($comment->user_id) >0)? $comment->user->name : $comment->name }}</h3>
                      <div class="meta">{{ date('d-M-Y', strtotime($comment->created_at)) }}</div>
                      <p>{!! $comment->comment !!}</p>
                    </div>
                  </li>
                  @endforeach
                </ul>
              @endif
              <!-- END comment-list -->
              
              <div class="comment-form-wrap pt-5">
                <h3 class="mb-5">Leave a comment</h3>
                  {!! Form::open(['url' => 'comment/add/'.$article->id, 'class' => 'validate-form', 'class' => 'p-5 bg-light']) !!}

                  @guest
                    <div class="form-group">
                      {{ Form::label ('Name') }}
                      {{ Form::text ('name','',array ('placeholder'=>'Enter your name', 'maxlength'=>50, 'class' => 'form-control', 'required' => 'required')) }}
                    </div>

                    <div class="form-group">
                      {{ Form::label ('Email') }}
                      {{ Form::text ('email','',array ('placeholder'=>'Enter your email id', 'maxlength'=>50, 'class' => 'form-control', 'required' => 'required')) }}
                    </div>

                    <div class="form-group ">
                      {{Form::label ('Mobile No')}}
                      <div class="input-group">
                        {{Form::text('mobile',null,array ('placeholder'=>'Enter mobile number','maxlength'=>'10','class' => 'form-control ', 'required' => 'required', 'id'=>'mobile'))}}                      
                        {!! Form::button('Verify',['class' => 'btn btn-warning btn-sm input-group-btn', 'id' => 'verify']) !!}
                      </div>
                      <span class="alert-info status"></span>
                    </div>

                    <div class="form-group">
                      {{Form::label ('Verification code')}}
                      {{Form::text('code', null, array ('placeholder'=>'Enter mobile verification code', 'class' => 'form-control', 'required' => 'required'))}}
                    </div>
                  @endguest

                  <div class="form-group">
                    {{ Form::label ('Comment') }}
                    {{ Form::textarea ('comment','',array ('placeholder'=>'Enter your comment','class' => 'form-control', 'required' => 'required')) }}
                  </div>
                  <div class="form-group">
                    <input type="submit" value="Post Comment" class="btn btn-primary">
                  </div>
                {!! Form::close() !!}
              </div>
            </div>
          </div>
            <!-- END main-content -->
        @include('site/wordify/side-bar')
        @includewhen($data['related_articles'], 'site/wordify/related-post', ['related_articles'=> $data['related_articles']])
            <!-- END sidebar -->   
        @endif
      </div>
    </div>
  </section>
@endsection

@section('scripts')
  <script type="text/javascript">
    $(document).ready(function() {
     $("#verify").click(function() {
      var mobile= $('#mobile').val();
      if( mobile != '')
      {
        $.ajax({
                  url: "{!! url('/verify-mobile/" + mobile + "') !!}",
                  method: 'GET',
                  success: function(data) {
                      $('.status').html(data);
                  }
              });
      }
      else
      {
        alert('please enter a valid mobile no');
      }
      });
    });
  </script>
@endsection