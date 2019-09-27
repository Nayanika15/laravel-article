@extends('layouts.wordify')
@section('content')
@php($latest_articles = $data['latest_articles'])
@php($popular_articles = $data['popular_articles'])
@php($active_categories = $data['active_categories'])
      <section class="site-section py-sm">
        <div class="container">
          <div class="row">
            <div class="col-md-6">
              <h2 class="mb-4">Latest Posts</h2>
            </div>
          </div>
          <div class="row blog-entries">
            <div class="col-md-12 col-lg-8 main-content">
              <div class="row">
                @if($latest_articles)
                  @foreach($latest_articles as $article)
                    <div class="col-md-6">
                      <a href="{{ url($article->permalink) }}" class="blog-entry element-animate" data-animate-effect="fadeIn">
                        <img src="{{ ($article->getMedia('articles')->count() > 0)?$article->getFirstMedia('articles')->getUrl('homepage'): asset('images/img_2.jpg') }}" alt="Image placeholder">
                        <div class="blog-content-body">
                          <div class="post-meta">
                            <span class="author mr-2"><img src="{{asset('images/person_1.jpg')}}" alt="Colorlib">{{ ($article->user()->first()->name && !empty($article->user())) ? $article->user()->first()->name : 'guest' }}</span>&bullet;
                            <span class="mr-2">{{ date('d-M-Y',strtotime($article->created_at)) }}</span> &bullet;
                            <span class="ml-2"><span class="fa fa-comments"></span> 3</span>
                          </div>
                          <h2>{{ $article->title }}</h2>
                        </div>
                      </a>
                    </div>
                 @endforeach
                @endif
              </div>
              <div class="row mt-5">
                <div class="col-md-12 text-center">
                  <nav aria-label="page navigation" class="text-center">
                    {{ $latest_articles->links() }}
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