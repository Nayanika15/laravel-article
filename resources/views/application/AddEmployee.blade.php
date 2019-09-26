
@extends('layouts.App')

@section('content')
   
        @if ($errors->any())
	        <div class="alert alert-danger">
	            <ul>
	                @foreach ($errors->all() as $error)
	                <li>{{ $error }}</li>
	                @endforeach
	            </ul>
	        </div>
        @endif
        <!-- TO CHECK IF MESSAGE IS SET IN SESSION AND ALERT THE MESSAGE -->
        @if (session('ErrorMessage'))
            <div class="alert alert-danger">
                {{ session('ErrorMessage') }}
            </div>
        @endif
    <!-- THIS WILL FETCH THE DATA IN UPDATE CASE AND AUTO POPULATE IT INTO THE TEXT FIELDS -->
        
      
    <!-- TO CHECK IF DATA IS PRESENT AND CHANGE THE PAGE TITLE ACCORDINGLY -->    
		@if(isset($id) &&  $id>0 )
	      @section('title', 'Update')
	 	@else
	      @section('title', 'Add')      
	  	@endif    

	  	
<div class="container">
    <div class="row" >
		<div class="col-sm-12" >
	      	<!-- FORM FOR ADD AND UPDATE CASE STARTS --> 
			<form method="post" action="/add/{{$employee->id??$employee->id??0}}" autocomplete="off" enctype="multipart/form-data">
				{{csrf_field()}}
	
				<div class="dataTable">
					<div class="form-group">
						<label class="col-lg-2 control-label ">Emp Name:</label>
						<div class="col-lg-4"><input type="text" class="form-control" name="name" value="{{old('name')??old('name')??$employee['name']}}"></div>
						
						<div class="clearfix"></div>	
					</div>

					<div class="form-group">
						<label class="col-lg-2">Designation:</label>
						
						<div class="col-lg-4"><input type="text" class="form-control" name="designation" value="{{old('designation')??old('designation')??$employee['designation']}}">
						</div>
						<div class="clearfix"></div>
					</div>
	
					<div class="form-group">{{old('employee_code')}}
						<label class="col-lg-2">Employee Id:</label>
						<div class="col-lg-4"><input type="text" class="form-control" name="employee_code" value="{{old('employee_code')??old('employee_code')??$employee['employee_code']}}"></div>
						<div class="clearfix"></div>
					</div>

					<div class="form-group">
						<label class="col-lg-2">Department:</label>
						<div class="col-lg-4"><input type="text" class="form-control" name="department" value="{{old('department')??old('department')??$employee['department']}}"></div>
						<div class="clearfix"></div>
					</div>
					
					<div class="form-group">

						<input class="btn btn-success" type="button" class="btn btn-success" name="add" value="{{isset($employee)?'Update':'Add'}}" onclick="this.form.submit();">
						<input class="btn btn-warning" type="reset" class="btn btn-success" name="reset" value="{{isset($employee)?'Cancel':'Reset'}}">
					</div>

				</div>			
			</form>
	    </div>
	

	</div>
</div>
	<!-- END FORM --> 
@endsection