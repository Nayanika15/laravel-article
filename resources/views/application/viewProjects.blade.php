@extends('layouts.App')

@section('content')
<!-- TO CHECK IF MESSAGE IS SET IN SESSION AND ALERT THE MESSAGE -->
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
<!-- TO CHECK IF MESSAGE IS SET IN SESSION AND ALERT THE MESSAGE -->
        @if (session('ErrorMessage'))
            <div class="alert alert-danger">
                {{ session('ErrorMessage') }}
            </div>
        @endif
        
<div class="container">
    <div class="row" >
        <div class="col-lg-12">
           @if($projects->count())
           <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Projects Assigned</th>                            
                        </tr>
                    </thead>
                    <tbody>                    
                      @foreach($projects as $project)
                    <tr>
                        <td>{{$project->project_title}}</td> 
                    </tr>
                    @endforeach 
                    </tbody>
            </table>
                    @else
                    <div class="noRecord">No Projects Assigned</div>
                    @endif  
                
        
        </div>
        <div class="clearfix"></div>
        <div class="col-lg-12">
        <form enctype="multipart/form-data" action="{{route('addProject',$employee_id)}}" method="post">
             @csrf
            <div class="form-group">
                <input type="text" name="project_title" placeholder="Enter project title">
                 <input type="hidden" name="employee_id" value="{{$employee_id}}">
                <div>
               <button class="btn btn-warning warning" type="submit">Assign Project</button>
           </div>
            </div>
            
        </form>
    </div>
    </div>
</div>

@endsection