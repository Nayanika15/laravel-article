<div class="row">
  <div class="col-md-12">
    <!-- TO CHECK IF ERROR MESSAGE IS SET IN SESSION AND ALERT THE MESSAGE -->
    @if(session('ErrorMessage'))
        <div class="alert alert-danger">
          {{ session('ErrorMessage') }}
        </div>
    @endif
    <!-- TO CHECK IF SUCCESS MESSAGE IS SET IN SESSION AND ALERT THE MESSAGE -->
    @if(session('success'))
        <div class="alert alert-success">
          {{ session('success') }}
        </div>
    @endif

    @if(session('errors'))
        <ul class="alert alert-danger">
          @foreach($errors->all() as $message )
            <li>{{ $message }}</li>
          @endforeach
        </ul>
    @endif
  </div>
</div>