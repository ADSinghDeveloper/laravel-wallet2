@extends('layouts.matim')

@php
  if($filter){
    $title = 'Edit';
  }else{
    $title = 'Add';
  }
@endphp

@section('pageTitle', $title . ' Filter')

@section('content')
<div class="card filter-block">
  <div class="card-header card-header-primary">
      <h3 class="card-title">@if($filter)<i class="material-icons pull-right">edit</i>@else<i class="material-icons pull-right">add</i>@endif {{ $title }} filter</h3>
  </div>
  <div class="card-body">
    <form action="{{ route('filter_save') }}" method="post">
      <div class="row">
        <div class="col-lg-6 col-md-6">
          <div class="form-group bmd-form-group">
            <label for="name" class="bmd-label-floating">Name</label>
            <input type="text" name="name" class="form-control" id="name" tabindex="1" required @if(old('name')) value="{{ old('name') }}" @elseif($filter) value="{{ $filter->name }}" @endif>
          </div>
        </div>
        <div class="col-lg-6 col-md-6">
          <div class="form-group bmd-form-group">
            <label for="sequence" class="bmd-label-floating">Sequence</label>
            <input type="number" name="sequence" class="form-control" id="sequence" tabindex="2" @if(old('sequence')) value="{{ old('sequence') }}" @elseif($filter) value="{{ $filter->sequence }}" @endif>
          </div>
        </div>
      </div>
      <div class="row">
          <div class="col-md-12 form-group">
              <label for="account">Select Account(s) to show filter on the dashboard</label>
              <div class="accounts multiple">
                  @foreach($accounts as $acct)
                  <label class="form-check form-check-inline form-check-label select-value">
                      <span class="badge" color-code="{{ $acct->color->code }}" style="@if((is_array($aids) && in_array($acct->id,$aids)) || (is_array(old('aids')) && in_array($acct->id,old('aids')))) background-color:@else color:@endif {{ $acct->color->code }};">{{ $acct->name }}</span>
                      <input type="checkbox" name="aids[]" value="{{ $acct->id }}" @if((is_array($aids) && in_array($acct->id,$aids)) || (is_array(old('aids')) && in_array($acct->id,old('aids')))) checked @endif>
                  </label>
                  @endforeach
              </div>
          </div>
      </div>
      <div class="row">
          <div class="col-md-12 form-group">
              <label for="category">Categories</label>
              <div class="categories multiple">
                  @foreach($categories as $category)
                  <label class="form-check form-check-inline form-check-label select-value">
                      <span class="badge badge-pill" color-code="{{ $category->color->code }}" style="@if(in_array($category->id,$cids) || (old('cids') && in_array($category->id,old('cids')))) background-color:@else color:@endif{{ $category->color->code }};">{{ $category->name }}</span>
                      <input type="checkbox" name="cids[]" value="{{ $category->id }}" @if(in_array($category->id,$cids) || old('cids') && in_array($category->id,old('cids'))) checked @endif>
                  </label>
                  @endforeach
              </div>
          </div>
      </div>
      <div class="row">
          <div class="col-md-12 form-group">
              <label for="type">Type</label>
              <div>
                  <div class="form-check d-inline-block togglebutton @if(!(old('type') || $type)) disabled @endif" id="type-toggle">
                      <label>
                          <span class="exp">Expense</span>
                          <input type="checkbox" id="trans-type" data-exp="1" data-inc="2" @if($type == 2 || old('type') == 2) checked="checked" @endif>
                          <span class="toggle"></span>
                          <span class="inc">Income</span>
                      </label>
                      <input type="hidden" id="type" value="@if($type){{ $type }}@elseif(old('type')){{ old('type') }}@endif" name="type">
                  </div>
                  <div class="form-check d-inline-block" style="margin-left: 20px; margin-top: 0;">
                      <label class="form-check-label">
                          <input class="form-check-input" id="no-type" name="notype" @if(!(old('type') || $type)) checked="checked" @endif type="checkbox"> Show Both
                          <span class="form-check-sign">
                              <span class="check"></span>
                          </span>
                      </label>
                  </div>
              </div>
          </div>
      </div>
      <div class="row">
          <div class="col-md-12 form-group">
              <label for="category">Payment Modes</label>
              <div class="form-check" style="margin: 0">
                  @foreach($paymentModes as $paymentMode)
                  <label class="form-check form-check-inline form-check-label">
                      <input class="form-check-input" type="checkbox" name="mods[]" value="{{ $paymentMode->id }}" @if(in_array($paymentMode->id,$mods) || old('mods') && in_array($paymentMode->id,old('mods'))) checked @endif>{{ $paymentMode->name }}
                      <span class="form-check-sign"><span class="check"></span></span>
                  </label>
                  @endforeach
              </div>
              <small><i>Select or un-select all will show the transactions of all the payment modes.</i></small>
          </div>
      </div>
      <div class="row">
          <div class="col-md-6">
            <div class="form-group bmd-form-group">
              <label for="name" class="bmd-label-floating">Search Kayword</label>
              <div class="input-group no-border">
                  <input type="text" name="search" value="@if(old('search')){{ old('search') }}@elseif(isset($search)){{ $search }}@endif" class="form-control">
                  <span class="clear search"><i class="material-icons">close</i></span>
              </div>
            </div>
          </div>
      </div>
      {{ csrf_field() }}
      @if($filter)
      <input type="hidden" name="fid" value="{{ $filter->id }}" />
      @endif
      <div class="form-group">
        <a href="{{ url()->previous() }}" class="btn btn-primary btn-round btn-fab" role="button" title="Back"><i class="material-icons">arrow_back</i></a>
        <button type="submit" class="btn btn-primary" title="Done"><i class="material-icons">done</i></button>
        @if($filter)
        <button type="button" rel="tooltip" title="Delete {{$filter->name}} filter" class="btn btn-danger btn-round btn-fab del pull-right" del_form_id="delFltr_{{ $filter->id }}">
            <i class="material-icons">delete</i>
        </button>
        @endif
      </div>
    </form>
    @if($filter)
    <form action="{{ route('filter_del')}}" method="POST" id="delFltr_{{ $filter->id }}">
        <input type="hidden" name="fid" value="{{ $filter->id }}" />
        {{ method_field('DELETE') }}
        {{ csrf_field() }}
    </form>
    @endif
  </div>
</div>
@endsection