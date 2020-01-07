@extends('layouts.wordify')
@section('title')
  {{ ' Category-' . env('SITE_TITLE') }}
@endsection

@section('content') 
  @php($categoryArticles = $data['categoryArticles'])
  @php($category = $data['category'])
  <section class="site-section pt-5">
    <div class="container">
      <div class="row mb-4">
        <div class="col-md-6">
          <h2 class="mb-4">Category: {{ $category->name }}</h2>
        </div>
      </div>
      <div class="row blog-entries">
        <div class="col-md-12 col-lg-8 main-content">
          <div class="row mb-5 mt-5">
            <div class="col-md-12">
              @foreach($categoryArticles as $article)
                <div class="post-entry-horzontal">
                  <a href="{{ url($article->permalink) }}">
                    <div class="image element-animate" data-animate-effect="fadeIn" style="background-image: url({{ ($article->getMedia('articles')->count() > 0) ? $article->getFirstMedia('articles')->getUrl('category') : asset('images/img_2.jpg') }});">
                    </div>
                    <span class="text">
                      <div class="post-meta">
                        <span class="author mr-2"><img src="{{ asset('images/person_1.jpg') }}" alt="Colorlib"> {{ ($article->user()->first()->name && !empty($article->user())) ? $article->user()->first()->name : 'guest' }}</span>&bullet;
                        <span class="mr-2">{{ date('d-M-Y',strtotime($article->created_at)) }}</span> &bullet;                        
                        @foreach($article->categories()->get() as $category)
                        <span class="mr-2">
                          {!! $category->name !!}
                        </span> &bullet;
                        @endforeach                       
                      </div>
                      <h2>{!! $article->title !!}</h2>
                      <p>{!! $article->excerpt !!}</p>
                     <h5 class="button btn btn-info">Read more</h5>
                    </span>
                  </a>
                </div>
              @endforeach
            </div>
             <!-- END post -->              
          </div>

          <div class="row mt-5">
            <div class="col-md-12 text-center">
              <nav aria-label="Page navigation" class="text-center">
                {{ $categoryArticles->links() }}
              </nav>
            </div>
          </div>
        </div>
        <!-- END main-content -->
        @include('site/wordify/side-bar')
         <!-- END sidebar -->
      </div>
    </div>
  </section>
@endsection
  
