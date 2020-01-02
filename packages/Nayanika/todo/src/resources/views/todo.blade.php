@extends('layouts.wordify')
@section('content')
<section class="site-section">
    <div class="container">
      <div class="row mb-4">
        <div class="col-md-6">
          <h1 class="mb-4">{{(isset($task->id))? 'Edit' : 'Add'}} Task</h1>
        </div>
      </div>
      <div class="row blog-entries">
        <div class="col-md-12 col-lg-8 main-content">
          @include('common.message')
            @if(isset($task))
                <h3>Edit : </h3>
                {!! Form::model($task, ['route'=>['update-task', $task->id], 'method' => 'patch']) !!}
            @else
                <h3>Add New Task : </h3>
                {!! Form::open(['route'=>'store-task']) !!}
            @endif
            <div class="row">
                <div class="col-md-8 form-group">
                @if(isset($task))
                    {!! Form::text('name',$task->name,['class' => 'form-control']) !!}
                @else
                    {!! Form::text('name',null,['class' => 'form-control']) !!}
                @endif
                </div>
                <div class="col-md-4 form-group">
                    {!! Form::submit($submit, ['class' => 'btn btn-primary form-control']) !!}
                </div>
            </div>
                {!! Form::close() !!}
            <hr>
            <h4>Tasks To Do : </h4>
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Name</th>
                            @if(Auth::user()->is_admin == 1)
                                <th>Added by</th>
                            @endif
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($tasks as $task)
                            <tr>
                                <td>{{ $task->name }}</td>
                                @if(Auth::user()->is_admin == 1)
                                    <td> {{ $task->user->name }} </td>
                                @endif
                                <td> {{ ($task->status == 1)? "Completed" : "In-Progress"}} </td>
                                <td>
                                    <div class='btn-group'>
                                        <a href="{!! route('edit-task', $task->id) !!}" class='btn btn-default btn-primary'><i class="fa fa-edit"></i></a>
                                    </div>
                                    @if(Auth::user()->is_admin == 1)
                                    <div class="btn-group">
                                        <a href="{!! route('update-status', $task->id)!!}"
                                            class="btn btn-default btn-primary"><i class="fa fa fa-check"></i></a>
                                    </div>
                                    @endif
                                    <div class="btn-group">
                                        <a href="{!! route('delete-task', $task->id)!!}"
                                            class="btn btn-default btn-primary"><i class="fa fa fa-trash"></i></a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
  </section>   
@endsection