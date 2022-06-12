@if (session('success'))
    <div class="alert alert-success">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <i class="material-icons">close</i>
        </button>
        <i class="material-icons">done</i>{!! session('success') !!}
    </div>
@endif
@if (session('warning'))
    <div class="alert alert-warning">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <i class="material-icons">close</i>
        </button>
        <i class="material-icons">warning</i>{!! session('warning') !!}
    </div>
@endif
@if (session('error'))
    <div class="alert alert-danger">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <i class="material-icons">close</i>
        </button>
        <i class="material-icons">error</i>{!! session('error') !!}
    </div>
@endif
@if ($errors->any())
    @foreach ($errors->all() as $error)
    <div class="alert alert-danger">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <i class="material-icons">close</i>
        </button>
        <i class="material-icons">error</i>{!! $error !!}
    </div>
    @endforeach
@endif