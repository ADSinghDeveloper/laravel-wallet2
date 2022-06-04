@extends('layouts.matim')

@section('pageTitle', 'Add Icon')

@section('content')
<div class="card">
    <div class="card-header card-header-primary">
        <h3 class="card-title"><i class="material-icons pull-right">add</i> Add Icon</h3>
    </div>
    <div class="card-body">
        <form action="{{ route('icon_save') }}" method="post">
          <div class="row">
            <div class="col-lg-6 col-md-6">
              <div class="form-group bmd-form-group">
                <label for="amount" class="bmd-label-floating">Icon Code</label>
                <input type="text" name="code" id="code" tabindex="1" class="form-control" required value="@if($icon != '') {{ $icon->code }} @endif" />
              </div>
            </div>
          </div>
          {{ csrf_field() }}
          @if($icon != '')
          <input type="hidden" name="cid" value="{{ $icon->id }}" />
          @endif
          <div class="form-group">
            <a href="{{ route('icons')}}" class="btn btn-primary btn-round btn-fab" role="button"><i class="material-icons">arrow_back</i></a>
            <button type="submit" class="btn btn-primary"><i class="material-icons">done</i></button>
          </div>
      </form>
    </div>
</div>
@endsection
