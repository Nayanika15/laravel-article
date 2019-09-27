@extends('layouts.wordify')
@section('title')
  {{ ((isset($article->id))?'Edit':'Add'). ' Article-' . env('SITE_TITLE') }}
@endsection

@section('content')
  <section class="site-section">
    <div class="container">
      <div class="row mb-4">
        <div class="col-md-6">
          <h1 class="mb-4">{{(isset($article->id))? 'Edit' : 'Add'}} Articles
            <small>
              <a href=" {{ route('all-articles') }} " class="btn btn-primary btn-xs rounded">All Articles</a>
            </small>
          </h1>           
        </div>
      </div>
      <div class="row blog-entries">
        <div class="col-md-12 col-lg-8 main-content">
          @if(isset($article->id))
            {!! Form::open(['url' => 'article/add/'.$article->id, 'id' => 'article-form', 'files' => true]) !!}
          @else
            {!! Form::open(['url' => 'article/add', 'id' => 'article-form', 'files' => true]) !!}
          @endif
            <div class="row">
              <div class="col-md-12 form-group">
                {{ Form::label ('Name') }}
                {{ Form::text ('title',($article->title)?? $article->title ??'',array ('placeholder'=>'Enter article title', 'maxlength'=>50, 'class' => 'form-control', 'required' => 'required')) }}
                
                @error('title')
                  <span class="validate-error" role="alert">
                    <strong>{{ $message }}</strong>
                  </span>
                @enderror
              </div>
              <div class="col-md-12 form-group">
                {{ Form::label ('Category Name') }}
                {{ Form::select('categories[]',$categories, (!empty($selected))? $selected : '', array('multiple' => true,'class' => 'form-control', 'required' => 'required')) }}

                @error('categories')
                  <span class="validate-error" role="alert">
                    <strong>{{ $message }}</strong>
                  </span>
                @enderror
              </div>
              <div class="col-md-12 form-group">
                {{ Form::label ('Article details') }}
                {{ Form::textarea('details',($article->details)?? $article->details ?? '', array ('placeholder'=>'Enter article details','class' => 'form-control summernote', 'required' => 'required')) }}
                
                @error('details')
                  <span class="validate-error" role="alert">
                    <strong>{{ $message }}</strong>
                  </span>
                @enderror
              </div>
              <div class="col-md-12 form-group">
                {{ Form::label ('Image') }}
                {{ Form::file('image', array('class' => 'form-control')) }}
                 @error('details')
                  <span class="validate-error" role="alert">
                    <strong>{{ $message }}</strong>
                  </span>
                @enderror
              </div>
              @if(!empty($article) && $article->getMedia('articles')->count() > 0)
              <div class="col-md-12 form-group">
                <img src="{{$article->getFirstMedia('articles')->getUrl('homepage')}}" alt="{{ $article->getFirstMedia('articles')->name }}">
              </div>
              @endif
              @if(auth()->user()->is_admin)
               <div class="col-md-12 form-group">
                {{ Form::label ('Status') }}
                {{ Form::select('approve_status', [ 0 =>'Unpublished', 1 =>'Published', 2 =>'Unapproved' ],($article->approve_status)?? $article->approve_status ?? '', array('class' => 'form-control', 'required' => 'required')) }}
              </div>
              @endif
              </div>
            <div class="row">
              <div class="col-md-6 form-group">

                {{ Form::submit((isset($article->id)?'Submit':'Add'), array('class'=>'btn btn-primary')) }}
                <a href="{{ url('/dashboard') }}" class="btn btn-danger">Cancel</a>
              </div>
            </div>
          {!! Form::close() !!}
        </div>
      </div>
    </div>
  </section>
@endsection
@section('scripts')
<link href="http://netdna.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.css" rel="stylesheet">
  <script src="http://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.js"></script> 
  <script src="http://netdna.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.js"></script> 
  <link href="http://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.12/summernote.css" rel="stylesheet">
  <script src="http://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.12/summernote.js"></script>
 <script>
   $(document).ready(function() {
        $('.summernote').summernote();
    });
  </script> 
@endsection
