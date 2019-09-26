
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
	        <div class="col-sm-2" style="float:right;"><a href="{{URL::to('view')}}">View Employee</a></div>
	        <div class="col-sm-2" style="float:right;"><a href="{{URL::to('')}}">Home</a></div>
	        <div class="col-sm-8" style="float:left;text-align: center;"><h3>Add Details</h3>
	        	<!-- FORM FOR ADD AND UPDATE CASE STARTS --> 
		<form method="post" action="" autocomplete="off" enctype="multipart/form-data">
			{{csrf_field()}}

			<table class="table" align="center">
				<tr>
					<td>Emp Name</td>
					<td><input type="text" class="form-control" name="empname" value="{{$employee['name']??$employee['name']??old('empname')}}"></td>
				</tr>
				<tr>
					<td>Designation</td>
					<td><input type="text" class="form-control" name="empdesg" value="{{$employee['designation']??$employee['designation']??old('empdesg')}}"></td>
					
				</tr>
				<tr>
					<td>Employee Id</td>
					<td><input type="text" class="form-control" name="empid" value="{{$employee['employee_code']??$employee['employee_code']??old('empid')}}"></td>
				</tr>
				<tr>
					<td>Department</td>
					<td><input type="text" class="form-control" name="empdept" value="{{$employee['department']??$employee['department']??old('empdept')}}"></td>
				</tr>
				
				<tr>

					<td><center><input type="submit" class="btn btn-success" name="add" value="{{isset($employee)?'Update':'Add'}}"></center></td>
				</tr>
			</table>
		</form>
	        </div>
	    </div>
	

	</div>
</div>
	<!-- END FORM --> 
@endsection