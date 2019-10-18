<section class="py-5">
  <div class="container">
    <div class="row">
      <div class="col-md-12">
        <h2 class="mb-3 ">Related Post</h2>
      </div>
    </div>
    @if($related_articles)
    <div class="row">
      @foreach($related_articles as $article)
      <div class="col-md-6 col-lg-4">
        <a href="{{ url($article->permalink) }}" class="a-block sm d-flex align-items-center height-md" style="background-image: url({{ ($article->getMedia('articles')->count() > 0) ? $article->getFirstMedia('articles')->getUrl('category') : asset('images/img_2.jpg') }}); ">
          <div class="text">
            <div class="post-meta">
              <span class="category">
                @foreach($article->categories as $category) 
                <small>{{ $category->name }}</small>  
                @endforeach
              </span>
              <span class="mr-2">{{ date('M d,Y', strtotime($article->created_at)) }}</span> &bullet;
              <span class="ml-2"><span class="fa fa-comments"></span>{{ $article->comments_count }}</span>
            </div>
            <h3>{{ $article->title }}</h3>
          </div>
        </a>
      </div>
      @endforeach
    </div>
    @endif
  </div>
</section>
<!-- END section -->