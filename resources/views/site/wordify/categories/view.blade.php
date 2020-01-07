@extends('layouts.wordify')

@section('title')
    View Categories - {{ env('SITE_TITLE') }}
@endsection

@section('content')
    <section class="site-section">
        <div class="container">
          <div class="row mb-4">
            <div class="col-md-6">
                <h1 class="mb-4">
                    All Categories
                    <small>
                        <a href="{{ route('add-category') }}" class="btn btn-primary btn-xs rounded">Add</a>
                    </small>
                </h1>
            </div>
          </div>
          
          <div class="row blog-entries">
            <div class="col-md-12 col-lg-12 main-content">
                @include('common.message')
                <table class="table data-table">
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th>Name</th>
                            <th>Created On</th>
                            <th width="200px">Action</th>
                        </tr>
                    </thead>
                    <tbody> </tbody>
                </table>
            </div>
          </div>
        </div>
    </section>
@endsection

@section('scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            $('.data-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{!! route('view-category') !!}",
                "order": [[ 1, "asc" ]],
                "columns": [
                    {data: 'id', name: 'id'},
                    {data: 'name', name: 'name'},
                    {data: 'created_at', name:'created_at'},
                    {data: 'action', name:'action'}
                ],
                "columnDefs": [
                    { "orderable": false, "targets": 3 }
                ]
            });
        });
    </script>
@endsection