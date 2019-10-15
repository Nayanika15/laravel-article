
      <footer class="site-footer">
        <div class="container">
          <div class="row mb-5">
            <div class="col-md-4">
              <h3>About Us</h3>
              <p class="mb-4">
                <img src="{{asset('images/img_1.jpg')}}" alt="Image placeholder" class="img-fluid">
              </p>

              <p>Lorem ipsum dolor sit amet sa ksal sk sa, consectetur adipisicing elit. Ipsa harum inventore reiciendis. <a href="#">Read More</a></p>
            </div>
            <div class="col-md-6 ml-auto">
              <div class="row">
                <div class="col-md-7">
                  @if(!empty($latest_articles))
                  <h3>Latest Article</h3>
                  <div class="post-entry-sidebar">
                    <ul>
                      @foreach($latest_articles as $article)
                      <li>
                        <a href="{{ url($article->permalink) }}">
                          <img src="{{ ($article->getMedia('articles')->count() > 0)?$article->getFirstMedia('articles')->getUrl('homepage'): asset('images/img_2.jpg') }}" alt="{{ $article->title }}" class="mr-4">
                          <div class="text">
                            <h4>{{ $article->title }}</h4>
                            <div class="post-meta">
                              <span class="mr-2">{{ date('d-M-Y',strtotime($article->created_at)) }}</span> &bullet;
                              <span class="ml-2"><span class="fa fa-comments"></span>{{ $article->comments_count }}</span>
                            </div>
                          </div>
                        </a>
                      </li>
                      @endforeach
                    </ul>
                  </div>
                  @endif
                </div>
                <div class="col-md-1"></div>
                
                <div class="col-md-4">

                  <div class="mb-5">
                    <h3>Quick Links</h3>
                    <ul class="list-unstyled">
                      <li><a href="{{ url('/') }}">Home</a></li>
                      @if(!empty($active_categories))
                        @foreach($active_categories as $category)
                          <li><a href="{{ url($category->permalink) }}">{{ $category->name }}</a></li>
                        @endforeach
                      @endif                      
                    </ul>
                  </div>
                  
                 <!--  <div class="mb-5">
                   <h3>Social</h3>
                   <ul class="list-unstyled footer-social">
                     <li><a href="#"><span class="fa fa-twitter"></span> Twitter</a></li>
                     <li><a href="#"><span class="fa fa-facebook"></span> Facebook</a></li>
                     <li><a href="#"><span class="fa fa-instagram"></span> Instagram</a></li>
                     <li><a href="#"><span class="fa fa-vimeo"></span> Vimeo</a></li>
                     <li><a href="#"><span class="fa fa-youtube-play"></span> Youtube</a></li>
                     <li><a href="#"><span class="fa fa-snapchat"></span> Snapshot</a></li>
                   </ul>
                 </div> -->
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12 text-center">
              <p class="small">
            <!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
            Copyright &copy;<script>document.write(new Date().getFullYear());</script> All Rights Reserved | This template is made with <i class="fa fa-heart text-danger" aria-hidden="true"></i>
            <!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
            </p>
            </div>
          </div>
        </div>
      </footer>