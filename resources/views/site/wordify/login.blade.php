@extends('layouts.wordify')

@section('title')
  Login - {{ env('SITE_TITLE') }}
@endsection

@section('content')
  <section class="site-section">
    <div class="container">
      <div class="row mb-4">
        <div class="col-md-6">
          <h1 class="mb-4">Login</h1>
        </div>
      </div>
      <div class="row blog-entries">
        <div class="col-md-8 col-lg-8 main-content">
          @include('common.message')
          {!! Form::open(['url' => 'login', 'class' => 'validate-form']) !!}
            <div class="row">
              <div class="col-md-12 form-group">
                {{ Form::label ('E-Mail Address') }}
                {{ Form::text ('email', null,array ('placeholder'=>'Enter email address','maxlength'=>50, 'class' => 'form-control', 'id' => 'email')) }}
                @error('email')
                  <span class="validate-error" role="alert">
                    <strong>{{ $message }}</strong>
                  </span>
                @enderror
              </div>

              <div class="col-md-12 form-group">
                {{ Form::label ('Password') }}
                {{ Form::password ('password', array ('placeholder'=>'Enter password', 'maxlength'=>50, 'class' => 'form-control', 'required' => 'required')) }}
                
                @error('password')
                  <span class="validate-error" role="alert">
                    <strong>{{ $message }}</strong>
                  </span>
                @enderror
              </div>
            </div>

            <div class="row">
              <div class="col-md-12 form-group">
                {{ Form::submit('Login', array('class'=>'btn btn-primary')) }}
                <a href="{{ url('/') }}" class="btn btn-danger">Cancel</a>
                <label>or</label> <a class="btn btn-lg btn-social-icon btn-google" href="/redirect/google" title="Sign in with Google" rel="nofollow"> <img src="{{ asset('images/google.png') }}"> Sign in with Google </a>
                <a href="{{ url('reset-password') }}">Forgot password</a>
              </div>
            </div>
          {!! Form::close() !!}
        </div>
      </div>
    </div>
  </section>
@endsection
