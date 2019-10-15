     
        <div class="container">
          <div class="row">
            <div class="col-md-12">
              <div class="owl-carousel owl-theme home-slider">
                @if(!empty($featured_articles))
                  @foreach($featured_articles as $article)
                  <div>
                    <a href="{{ url($article->permalink) }}" class="a-block d-flex align-items-center height-lg" style="background-image: url({{ ($article->getMedia('articles')->count() > 0)?$article->getFirstMedia('articles')->getUrl('slider'): asset('images/img_2.jpg') }})">
                      <div class="text half-to-full">
                        @foreach($article->categories()->get() as $category)
                          <span class="category mb-5">
                            {!! $category->name !!}
                          </span> &bullet;
                          @endforeach
                          <div class="post-meta">
                            <span class="author mr-2">
                              <img src="{{ asset('images/person_1.jpg') }}" alt="Colorlib"> {{ ($article->user()->first()->name && !empty($article->user())) ? $article->user()->first()->name : 'guest' }}
                            </span>&bullet;
                            <span class="mr-2">
                              {{ date('d-M-Y',strtotime($article->created_at)) }}
                            </span> &bullet;
                            <span class="ml-2">
                              <span class="fa fa-comments"></span>
                              {{ $article->comments_count }}
                            </span>                            
                          </div>
                        <h3>{!! $article->title !!}</h3>
                        <p>{!! $article->excerpt !!}</p>
                      </div>
                    </a>
                  </div>
                @endforeach
                @endif
              </div>
              
            </div>
          </div>
          
        </div>
      <!-- END section -->