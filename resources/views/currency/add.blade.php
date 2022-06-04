@extends('layouts.matim')

@php
  if($currency != ''){
    $title = 'Edit';
  }else{
    $title = 'Add';
  }
@endphp

@section('pageTitle', $title . ' Currency')

@section('content')
<div class="card">
  <div class="card-header card-header-primary">
    <h3 class="card-title">@if($currency != '')<i class="material-icons pull-right">edit</i>@else<i class="material-icons pull-right">add</i>@endif {{ $title }} Currency</h3>
  </div>
  <div class="card-body">
  <form action="{{ route('currency_save') }}" method="post">
    <div class="row">
      <div class="col-lg-6 col-md-6">
        <div class="form-group bmd-form-group">
          <label for="name" class="bmd-label-floating">Name</label>
          <input type="text" name="name" class="form-control" id="name" tabindex="1" required @if(old('name')) value="{{ old('name') }}" @elseif($currency != '') value="{{ $currency->name }}" @endif />
        </div>
      </div>
      <div class="col-lg-6 col-md-6">
        <div class="form-group bmd-form-group">
          <label for="code" class="bmd-label-floating">Currency Code</label>
          <input type="text" name="code" class="form-control" id="code" tabindex="2" required @if(old('code')) value="{{ old('code') }}" @elseif($currency != '') value="{{ $currency->code }}" @endif />
          <small>Example: Enter HTML currency code "&amp;#36;" for &#36;</small>
        </div>
      </div>
    </div>
    {{ csrf_field() }}
    @if($currency != '')
    <input type="hidden" name="cid" value="{{ $currency->id }}" />
    @endif
    <a href="{{ route('currencies')}}" class="btn btn-primary btn-round btn-fab" role="button"><i class="material-icons">arrow_back</i></a>
    <button type="submit" class="btn btn-primary"><i class="material-icons">done</i></button>
    @if($currency != '')
    <a href="{{ route('currency_del',[$currency->id])}}" class="btn btn-danger btn-round btn-fab pull-right" title="Delete" alt="Delete"><i class="material-icons">delete</i></a>
    @endif
  </form>
  </div>
</div>
@endsection
