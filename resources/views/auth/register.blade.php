@extends('layouts.App')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ 'Register' }}</div>

                <div class="card-body">
                    {!! Form::open(['url' => 'register']) !!}
                        <div class="form-group row">
                            <div class="col-md-6">
                                {{ Form::label ('User Name')}}
                                {{ Form::text ('name',null, array ('placeholder'=>'Enter Username','maxlength'=>30,'class' => 'form-control'))}}
                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                             <div class="col-md-6">
                                {{Form::label ('E-Mail Address')}}
                                {{Form::text ('email',null,array ('placeholder'=>'Enter email address','maxlength'=>50,'class' => 'form-control'))}}
                               @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                             <div class="col-md-6">
                                {{Form::label ('Mobile No')}}
                                {{Form::text ('mobile',null,array ('placeholder'=>'Enter mobile no','maxlength'=>'10','class' => 'form-control'))}}
                              @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                             <div class="col-md-6">
                                {{Form::label ('Password')}}
                                {{Form::password ('password',array ('placeholder'=>'Enter password','maxlength'=>50,'class' => 'form-control'))}}
                              @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-6">
                                {{Form::label ('Confirm Password')}}
                                {{Form::password ('password_confirmation',array ('placeholder'=>'Confirm password','maxlength'=>50,'class' => 'form-control'))}}
                              @error('password_confirmation')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ 'Register'}}
                                </button>
                            </div>
                        </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
