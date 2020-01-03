@extends('layouts.wordify')
@section('title')
  Register - {{ env('SITE_TITLE') }}
@endsection

@section('content')
  <section class="site-section">
    <div class="container">
      <div class="row mb-4">
        <div class="col-md-6">
          <h1 class="mb-4">Register</h1>
        </div>
      </div>

      <div class="row blog-entries">
        <div class="col-md-12 col-lg-8 main-content"> 
          @include('common.message')
            {!! Form::open(['url' => 'register', 'class' => 'validate-form']) !!}
              <div class="row">
               <div class="col-md-12 form-group">
                  {{ Form::label ('User Name')}}
                  {{ Form::text ('name',null, array ('placeholder'=>'Enter Username','maxlength'=>30,'class' => 'form-control', 'required' => 'required'))}}
                  @error('name')
                  <span class="invalid-feedback" role="alert">
                      <strong>{{ $message }}</strong>
                  </span>
                  @enderror
                </div>
                <div class="col-md-12 form-group">
                  {{Form::label ('E-Mail Address')}}
                  {{Form::email ('email',null,array ('placeholder'=>'Enter email address','maxlength'=>50,'class' => 'form-control', 'required' => 'required'))}}
                  @error('email')
                  <span class="invalid-feedback" role="alert">
                      <strong>{{ $message }}</strong>
                  </span>
                  @enderror
              </div>
              <div class="col-md-12 form-group ">
                {{Form::label ('Mobile No')}}
                <div class="input-group">
                  {{Form::text('mobile',null,array ('placeholder'=>'Enter mobile no','maxlength'=>'10','class' => 'form-control ', 'required' => 'required', 'id'=>'mobile'))}}
                  {!! Form::button('Verify',['class' => 'btn btn-warning btn-sm input-group-btn', 'id' => 'verify']) !!}
                </div>
                <span class="alert-info status"></span>
              </div>
              <div class="col-md-12 form-group">
                {{Form::label ('Verification code')}}
                {{Form::text('code', null, array ('placeholder'=>'Enter mobile verification code', 'class' => 'form-control', 'required' => 'required'))}}
              </div>

              <div class="col-md-12 form-group">
                {{Form::label ('Password')}}
                {{Form::password ('password',array ('placeholder'=>'Enter password','minlength'=>5,'class' => 'form-control', 'required' => 'required'))}}
                @error('password')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
              </div>
              <div class="col-md-12 form-group">
                {{Form::label ('Confirm Password')}}
                {{Form::password ('password_confirmation',array ('placeholder'=>'Confirm password','minlength'=>5,'class' => 'form-control', 'required' => 'required'))}}
                @error('password_confirmation')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
              </div>
            </div>
            <div class="row">
              <div class="col-md-6 form-group">
                {{ Form::submit('Register', array('class'=>'btn btn-primary')) }}
                <a href="{{ url('/') }}" class="btn btn-danger">Cancel</a>
              </div>
            </div>
          {!! Form::close() !!}
        </div>
      </div>
    </div>
  </section>
@endsection
@section('scripts')
<script type="text/javascript">
  $(document).ready(function() {
   $("#verify").click(function()
   {
      var mobile= $('#mobile').val();
      if( mobile != '')
      {
        $.ajax({
                  url: "{!! url('/verify-mobile/" + mobile + "') !!}",
                  method: 'GET',
                  success: function(data) {
                      $('.status').html(data);
                  }
              });
      }
      else
      {
        alert("Please enter a valid mobile number.");
      }
    });
  });
</script>
@endsection