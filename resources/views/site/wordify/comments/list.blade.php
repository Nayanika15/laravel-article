@extends('layouts.wordify')

@section('title')
    All Comments - {{ env('SITE_TITLE') }}
@endsection

@section('content')
    <section class="site-section">
        <div class="container">
          <div class="row mb-4">
            <div class="col-md-6">
                <h1 class="mb-4">
                    All Comments
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
                            <th>Article</th>
                            <th>Comment</th>
                            <th>Created By</th>
                            <th>Created On</th>
                            <th>Status</th>
                            <th width="200px">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                       
                    </tbody>
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
            ajax: "{!! route('all-comments') !!}",
            "order": [[ 1, "asc" ]],
            "columns": [
                {data: 'id', name: 'id'},
                {data: 'article_title', name: 'article_title'},
                {data: 'comment', name: 'comment'},            
                {data: 'user_id', name:'user_id'},
                {data: 'created_at', name:'created_at'},
                {data: 'approve_status', name:'approve_status'},
                {data: 'action', name:'action'}
            ],
            "columnDefs": [
                { "orderable": false, "targets": 6 }
            ]
        });
    });
    </script>
@endsection