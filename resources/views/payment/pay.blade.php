@extends('layouts.wordify')
@section('title')
  {{  ' Payment-' . env('SITE_TITLE') }}
@endsection
@section('content')
    <section class="site-section">
    <div class="container">
      <div class="row mb-4">
        <div class="col-md-6">
          <h1 class="mb-4">Payment details</h1>
        </div>
      </div>
      
      <div class="row blog-entries">
        <div class="col-md-12 col-lg-8 main-content">
            @include('payment.form')
        </div>
      </div>
    </div>
  </section>
@endsection