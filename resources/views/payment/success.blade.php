@extends('layouts.wordify')
@section('title')
  Pay -{{ env('SITE_TITLE') }}
@endsection
@section('content')
    <section class="site-section">
    <div class="container">
      <div class="row mb-4">
        <div class="col-md-6">
          <h1 class="mb-4">Payment Successfull !!!</h1>
        </div>
      </div>
      
      <div class="row blog-entries">
        <div class="col-md-12 col-lg-12 main-content">
           <div class="alert-box">
            <div class="alert alert-success">
              <div class="alert-icon text-center">
                <i class="fa fa-check-square-o  fa-3x" aria-hidden="true"></i>
              </div>
              <div class="alert-message text-center">
                <strong>Payment Success!</strong> Your article is submitted for approval.
              </div>
              <div class="text-center">        
                <small>
                  <a href=" {{ route('all-articles') }} " class="btn btn-success btn-xs rounded">
                  <i class="fa fa-arrow-left" style="font-size:20px;"></i> All Articles</a>
                </small>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
@endsection

