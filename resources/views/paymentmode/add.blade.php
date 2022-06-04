@extends('layouts.matim')

@php
  if($paymentmode != ''){
    $title = 'Edit';
  }else{
    $title = 'Add';
  }
@endphp

@section('pageTitle', $title . ' Payment Mode')

@section('content')
<div class="card">
    <div class="card-header card-header-primary">
        <h3 class="card-title">@if($paymentmode != '')<i class="material-icons pull-right">edit</i>@else<i class="material-icons pull-right">add</i>@endif {{ $title }} Payment Mode</h3>
    </div>
    <div class="card-body">
        <form action="{{ route('paymentmode_save') }}" method="post">
          <div class="row">
            <div class="col-lg-6 col-md-6">
              <div class="form-group bmd-form-group">
                <label for="name" class="bmd-label-floating">Name</label>
                <input type="text" name="name" class="form-control" id="name" tabindex="1" required @if(old('name')) value="{{ old('name') }}" @elseif($paymentmode != '') value="{{ $paymentmode->name }}" @endif />
              </div>
            </div>
          </div>
          {{ csrf_field() }}
          @if($paymentmode != '')
          <input type="hidden" name="cid" value="{{ $paymentmode->id }}" />
          @endif
          <a href="{{ route('paymentmodes')}}" class="btn btn-primary btn-round btn-fab" role="button" title="Back"><i class="material-icons">arrow_back</i></a>
          <button type="submit" class="btn btn-primary" title="Done"><i class="material-icons">done</i></button>
          @if($paymentmode != '')
          <a href="{{ route('paymentmode_del',[$paymentmode->id])}}" class="btn btn-danger btn-round btn-fab pull-right"  title="Delete" alt="Delete"><i class="material-icons">delete</i></a>
          @endif
        </form>
    </div>
</div>
@endsection
