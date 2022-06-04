@extends('layouts.matim')

@section('pageTitle', 'Add Color')

@section('content')
<div class="card">
    <div class="card-header card-header-primary">
        <h3 class="card-title">@if($color != '')Edit @else <i class="material-icons pull-right">add</i> @endif Color</h3>
    </div>
    <div class="card-body">
        <form action="{{ route('color_save') }}" method="post">
          <div class="form-group">
            <label for="amount">Color Code</label>
            <input type="color" name="code" id="code" required value="@if($color != '') {{ strtoupper($color->code) }} @endif" placeholder="Color Code" />
          </div>
          {{ csrf_field() }}
          @if($color != '')
          <input type="hidden" name="cid" value="{{ $color->id }}" />
          @endif
          <div class="form-group">
            <a href="{{ route('colors')}}" class="btn btn-primary btn-round btn-fab" role="button"><i class="material-icons">arrow_back</i></a>
            <button type="submit" class="btn btn-primary"><i class="material-icons">done</i></button>
          </div>
      </form>
    </div>
</div>
@endsection
