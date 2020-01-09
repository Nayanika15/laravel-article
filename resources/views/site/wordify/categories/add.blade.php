@extends('layouts.wordify')
@section('title')
  {{ ((isset($categories->id))?'Edit':'Add'). ' Category-' . env('SITE_TITLE') }}
@endsection

@section('content')
   <section class="site-section">
       <div class="container">
         <div class="row mb-4">
           <div class="col-md-6">
             <h1 class="mb-4">{{(isset($categories->id))? 'Edit' : 'Add'}} Category</h1>
           </div>
         </div>
         <div class="row blog-entries">
           <div class="col-md-12 col-lg-8 main-content">
             @include('common.message')
             @if(isset($categories->id))
               {!! Form::open(['url' => 'admin/add-category/'.$categories->id, 'class' => 'validate-form']) !!}
             @else
               {!! Form::open(['url' => 'admin/add-category', 'class' => 'validate-form']) !!}
             @endif
               <div class="row">
                 <div class="col-md-12 form-group">
                   {{ Form::label ('Category Name') }}
                   {{ Form::text ('name',($categories->name)?? $categories->name ??'',array ('placeholder'=>'Enter category name', 'maxlength'=>50, 'class' => 'form-control', 'id' => 'name')) }}
                   
                   @error('name')
                     <span class="validate-error" role="alert">
                       <strong>{{ $message }}</strong>
                     </span>
                   @enderror
                 </div>
               </div>

               <div class="row">
                 <div class="col-md-6 form-group">
                   {{ Form::submit((isset($categories->name)?'Submit':'Add'), array('class'=>'btn btn-primary')) }}
                 </div>
               </div>
             {!! Form::close() !!}
           </div>
         </div>
      </div>
   </section>
@endsection
