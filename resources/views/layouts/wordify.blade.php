<!doctype html>
<html lang="en">
  <head>
    <title>@yield('title')</title>    
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link href="https://fonts.googleapis.com/css?family=Josefin+Sans:300, 400,700|Inconsolata:400,700" rel="stylesheet">

    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
    <link rel="stylesheet" href="{{ mix('fonts/fontawesome/css/font-awesome.min.css') }}">
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
    <div class="wrap" id="app">
      <!-- Adding header -->
      @include('site.wordify.header')
      <!-- END header -->

      <!-- Adding content -->
      @yield('content')
      <!-- END content -->

      <!-- Adding footer -->
      @include('site.wordify.footer')
      <!-- END footer -->
    </div>
    
    <!-- loader -->
    <div id="loader" class="show fullscreen"><svg class="circular" width="48px" height="48px"><circle class="path-bg" cx="24" cy="24" r="22" fill="none" stroke-width="4" stroke="#eeeeee"/><circle class="path" cx="24" cy="24" r="22" fill="none" stroke-width="4" stroke-miterlimit="10" stroke="#f4b214"/></svg></div>
    <script src="{{ mix('/js/app.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/additional-methods.min.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
    <script src="{{ mix('/js/main.js') }}"></script>
   @yield('scripts')
  </body>
    </html>