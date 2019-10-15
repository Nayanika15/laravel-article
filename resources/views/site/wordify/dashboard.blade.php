@extends('layouts.wordify')
@section('title')
  Dashboard-{{ env('SITE_TITLE') }}
@endsection
@section('content')
	<section class="site-section">
    <div class="container">
      <div class="row mb-4">
        <div class="col-md-6">
          <h1 class="mb-4">Dashboard</h1>
        </div>
      </div>
      
      <div class="row blog-entries">
        <div class="col-md-12 col-lg-8 main-content">
          @if(isset($service))
          <div class="title m-b-md">
              Welcome {{ $details->user['name']}} ! <br> Email id : {{
              $details->user['email'] }} <br> This is your dashboard.
          </div>
          @else
            Hi <span class="user">{{ Auth::user()->name }}</span> this is your dashboard.
          @endif
          
        </div>
      </div>
    </div>
  </section>
@endsection