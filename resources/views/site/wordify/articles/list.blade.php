@extends('layouts.wordify')

@section('title')
    All Articles - {{ env('SITE_TITLE') }}
@endsection

@section('content')
    <section class="site-section">
        <div class="container">
            <div class="row mb-4">
                <div class="col-md-6">
                    <h1 class="mb-4">
                        All Articles

                        <small>
                            <a href="{{ route('add-article') }}" class="btn btn-primary btn-xs rounded">Add Article</a>
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
                                <th>Title</th>
                                <!-- <th>Details</th> -->
                                <th>Payment Status</th>
                                <th>Status</th>
                                <th>Created By</th>
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
            ajax: "{!! route('all-articles') !!}",
            "order": [[ 1, "asc" ]],
            "columns": [
                {data: 'id', name: 'id'},
                {data: 'title', name: 'title'},
                {data: 'paid_status', name:'paid_status'},
                {data: 'approve_status', name:'approve_status'},
                {data: 'user_id', name:'user_id'},
                {data: 'created_at', name:'created_at'},
                {data: 'action', name:'action'}
            ],
            "columnDefs": [
                { "orderable": false, "targets": 6 }
            ]
        });
    }); 

    </script>
@endsection