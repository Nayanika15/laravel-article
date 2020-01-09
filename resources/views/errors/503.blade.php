@extends('layouts.wordify')
@section('title')
  Dashboard-{{ env('SITE_TITLE') }}
@endsection
@section('content')
	<section class="site-section">
		<div class="container">
		  <div class="row mb-4">
        <div class="col-md-6">
          <h1 class="mb-4">Server down!!!</h1>
        </div>
      	</div>
		  <div class="row blog-entries">
		    <div class="col-md-8 col-lg-12 main-content">
				<div id="errError">
					<div class="errError">
						<div class="err">
							<h1>5 <span class="e503"></span> 3</h1>
						</div>
						<h2>Oops! Server is on maintainance mode</h2>
						<p>Sorry we are temporarily unavailable. Please visit us later.</p>
					</div>
				</div>
			</div>
			</div>
		</div>
	</section>
@endsection