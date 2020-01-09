@extends('layouts.wordify')
@section('title')
  Dashboard-{{ env('SITE_TITLE') }}
@endsection
@section('content')
	<section class="site-section">
		<div class="container">
		  <div class="row mb-4">
        <div class="col-md-6">
          <h1 class="mb-4">Page not found!!!</h1>
        </div>
      	</div>
		  <div class="row blog-entries">
		    <div class="col-md-8 col-lg-12 main-content">
				<div id="errError">
					<div class="errError">
						<div class="err">
							<h1>4<span class="e404"></span>4</h1>
						</div>
						<h2>Oops! Page Not Be Found</h2>
						<p>Sorry but the page you are looking for does not exist, have been removed. name changed or is temporarily unavailable</p>
						<a href="{{ url('/') }}">Back to homepage</a>
					</div>
				</div>
			</div>
			</div>
		</div>
	</section>
@endsection