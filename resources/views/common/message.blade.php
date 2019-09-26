<div class="row">
  <div class="col-md-12">
    <!-- TO CHECK IF MESSAGE IS SET IN SESSION AND ALERT THE MESSAGE -->
    @if (session('ErrorMessage'))
        <div class="alert alert-danger">
          {{ session('ErrorMessage') }}
        </div>
    @endif
    <!-- TO CHECK IF MESSAGE IS SET IN SESSION AND ALERT THE MESSAGE -->
    @if (session('success'))
        <div class="alert alert-success">
          {{ session('success') }}
        </div>
    @endif
  </div>
</div>