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
       <table class="table table-bordered">
                <thead>
                    <tr>
                        <td>Name</td>
                        <td>Designation</td>
                        <td>Employee Id</td>
                        <td>Department</td>
                        <td>Projects Assigned</td>
                        <td>Action</td>
                    </tr>
                </thead>
                <tbody>
                    @if($employee)
                  @foreach($employee as $emp)
                    <tr>
                        <td>{{$emp->name }}</td>
                        <td>{{$emp->designation}}</td>
                        <td>{{$emp->employee_code}}</td>
                        <td>{{$emp->department}}</td>
                        <td>@if($emp->projects->count())
                            @foreach($emp->projects as $project)
                            <form action="{{route('updateStatus',$project->id)}}" method="post" >
                            @csrf               
                                <label class="checkbox" for="completed">
                                    <input type="checkbox" name="completed" onchange="this.form.submit()" {{ ($project->completed)?'checked':''}}>
                                        {{$project->project_title}}
                                </label>
                            </form>
                        @endforeach
                        @else
                        <span class="btn-danger">No projects assigned</span>
                        @endif
                        </td>
                        <td>
                            <form action="{{ action('EmployeeController@destroy', $emp->id)}}" method="post">
                            @csrf
                            @method('DELETE')
                                <button class="btn btn-danger" type="submit">Delete</button>
                            </form>
                            <form action="{{ route('assignProject', $emp->id)}}" method="GET">
                            @csrf
                            <button class="btn btn-warning warning" type="submit">Assign Project</button>
                            </form>
                            <a href="add/{{$emp->id}}">Edit Employee</a>
                        </td>
                    </tr>
                @endforeach 
            </tbody>
        </table>
                @else
                <div class="noRecord">No records Found</div>
                @endif  
                
    </div>
</div>

@endsection