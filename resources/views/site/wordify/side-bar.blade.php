<div class="col-md-12 col-lg-4 sidebar">
  <div class="sidebar-box">
    <h3 class="heading">Popular Article</h3>
    <div class="post-entry-sidebar">
      @if(!empty($popular_articles))
      <ul>
        @foreach($popular_articles as $article)
          <li>
            <a href="{{ url($article->permalink) }}">
              <img src="{{ ($article->getMedia('articles')->count() > 0)?$article->getFirstMedia('articles')->getUrl('homepage'): asset('images/img_2.jpg') }}" alt="{{ ($article->getMedia('articles')->count() > 0) ?($article->getMedia('articles')->first()->name) : '' }}" class="mr-4">
              <div class="text">
                <h4>{{ $article->title }}</h4>
                <div class="post-meta">
                  <span class="mr-2">{{ date('d-M-Y', strtotime($article->created_at)) }}</span>
                </div>
              </div>
            </a>
          </li>
        @endforeach
      </ul>
      @endif
    </div>
  </div>
  <!-- END sidebar-box -->

  <div class="sidebar-box">
    <h3 class="heading">Categories</h3>
    @if($active_categories)
      <ul class="categories">
        @foreach($active_categories as $category)
          <li><a href="{{ url($category->permalink) }}">{{ $category->name }}<span>{{ $category->articles_count }}</span></a></li>
        @endforeach
      </ul>
    @endif
  </div>
  <!-- END sidebar-box -->
</div>