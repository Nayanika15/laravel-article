<header role="banner">
        <div class="top-bar">
          <div class="container">
            <div class="row">
              <div class="col-9 social">
                @guest
                  <a href="#">Register</a>
                  <a href="{{ route('login') }}">Login</a>
                @else
                  <span class="user">Welcome <strong>{{ auth()->user()->name }}</strong>!</span> 
                  <a href="{{ route('logout') }}">Logout</a>
                @endguest
              </div>
              <div class="col-3 search-top" style="display: none;">
                <!-- <a href="#"><span class="fa fa-search"></span></a> -->
                <form action="#" class="search-top-form">
                  <span class="icon fa fa-search"></span>
                  <input type="text" id="s" placeholder="Type keyword to search...">
                </form>
              </div>
            </div>
          </div>
        </div>

        <div class="container logo-wrap">
          <div class="row pt-5">
            <div class="col-12 text-center">
              <a class="absolute-toggle d-block d-md-none" data-toggle="collapse" href="#navbarMenu" role="button" aria-expanded="false" aria-controls="navbarMenu"><span class="burger-lines"></span></a>
              <h1 class="site-logo"><a href="/">{{ env('SITE_TITLE') }}</a></h1>
            </div>
          </div>
        </div>
        
        <nav class="navbar navbar-expand-md  navbar-light bg-light">
          <div class="container">           
            <div class="collapse navbar-collapse" id="navbarMenu">
              <ul class="navbar-nav mx-auto">
                <li class="nav-item">
                  <a class="nav-link active" href="/">Home</a>
                </li>

                <li class="nav-item dropdown">
                  <a class="nav-link dropdown-toggle" href="category.html" id="dropdown05" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Categories</a>
                  <div class="dropdown-menu" aria-labelledby="dropdown05">
                    <a class="dropdown-item" href="category.html">Lifestyle</a>
                    <a class="dropdown-item" href="category.html">Food</a>
                    <a class="dropdown-item" href="category.html">Adventure</a>
                    <a class="dropdown-item" href="category.html">Travel</a>
                    <a class="dropdown-item" href="category.html">Business</a>
                  </div>

                </li>
                <li class="nav-item">
                  <a class="nav-link" href="about.html">About</a>
                </li>
                @if(!empty(Auth::user()))
                <li class="nav-item dropdown">
                  <a class="nav-link dropdown-toggle" href="category.html" id="dropdown05" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">{{ (Auth::user()->is_admin == 1)?'Admin':'User' }}</a>
                  <div class="dropdown-menu" aria-labelledby="dropdown05">
                    <a class="dropdown-item" href="{{ route('dashboard') }}">Dashboard</a>
                    @if(Auth::user()->is_admin == 1)
                      <a class="dropdown-item" href="{{ route('view-category') }}">
                    Categories</a>
                    @endif
                      <a class="dropdown-item" href="{{ route('all-articles') }}">Articles</a>
                      <a class="dropdown-item" href="category.html">Comments</a>                    
                  </div>

                </li>
                @endif
              </ul>
              
            </div>
          </div>
        </nav>
      </header>