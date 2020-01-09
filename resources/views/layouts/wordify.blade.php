<!doctype html>
<html lang="en">
  <head>
    <title>@yield('title')</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link href="https://fonts.googleapis.com/css?family=Josefin+Sans:300, 400,700|Inconsolata:400,700" rel="stylesheet">

    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:300,700" rel="stylesheet">
    
    <style type="text/css">
      .validate-error{
        width: 100%;
        margin-top: 0.25rem;
        font-size: 80%;
        color: #dc3545;
        }
      .user{
        color: #b71e1e;
        }
    </style>
  </head>

  <body>
    <div class="wrap">
      @include('site.wordify.header')
           @yield('content')
      
      @include('site.wordify.footer')
      <!-- END footer -->
    </div>
    
    <!-- loader -->
    <div id="loader" class="show fullscreen"><svg class="circular" width="48px" height="48px"><circle class="path-bg" cx="24" cy="24" r="22" fill="none" stroke-width="4" stroke="#eeeeee"/><circle class="path" cx="24" cy="24" r="22" fill="none" stroke-width="4" stroke-miterlimit="10" stroke="#f4b214"/></svg></div>

    <script src="{{ mix('/js/jquery-3.2.1.min.js') }}"></script>
    <script src="{{ mix('/js/jquery-migrate-3.0.0.js') }}"></script>
    
    <script src="{{ mix('/js/popper.min.js') }}"></script>
    <script src="{{ mix('/js/bootstrap.min.js') }}"></script>
    <script src="{{ mix('/js/owl.carousel.min.js') }}"></script>
    <script src="{{ mix('/js/jquery.waypoints.min.js') }}"></script>
    <script src="{{ mix('/js/jquery.stellar.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/additional-methods.min.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
   <script src="{{ mix('/js/main.js') }}"></script>
   <script src="{{ mix('/js/validatorFile.js') }}" ></script>
   @yield('scripts')
  </body>
    </html>