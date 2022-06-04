@extends('layouts.matim')

@php
  if($account != ''){
    $title = 'Edit';
  }else{
    $title = 'New';
  }
@endphp

@section('pageTitle', $title . ' Account')

@section('content')
<div class="card">
  <div class="card-header card-header-primary" @if($account != '') style="background: {{ $account->color->code }}" @endif>
      <h3 class="card-title">@if($account != '')<i class="material-icons pull-right">edit</i>@else<i class="material-icons pull-right">add</i>@endif{{ $title }} Account </h3>
  </div>
    <div class="card-body">
        <form action="{{ route('account_save') }}" method="post">
          <div class="row">
            <div class="col-lg-6 col-md-6">
              <div class="form-group bmd-form-group">
                <label for="name" class="bmd-label-floating">Name</label>
                <input type="text" name="name" class="form-control" id="name" tabindex="1" required @if(old('name')) value="{{ old('name') }}" @elseif($account) value="{{ $account->name }}" @endif>
              </div>
            </div>
            <div class="col-lg-6 col-md-6">
              <div class="form-group bmd-form-group">
                <label for="sequence" class="bmd-label-floating">Sequence</label>
                <input type="number" name="sequence" class="form-control" id="sequence" tabindex="2" @if(old('sequence')) value="{{ old('sequence') }}" @elseif($account) value="{{ $account->sequence }}" @endif>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-lg-6 col-md-6">
              <div class="form-group" data-toggle="modal" data-target="#currencyPopup">
                <label for="currency">Currency</label>
                <div class="icons select-block" id="selected_currency">
                  @if($account != '')
                  <label class="form-check form-check-inline form-check-label select-value">
                    <span class="icons-view"><i class="material-icons placeholder">{!! strtoupper($account->currency->code) !!}</i></span>
                  </label>
                  @else
                  <p>Select Currency</p>
                  @endif
                </div>
                <input type="hidden" id="currency_id" name="currency_id" @if(old('currency_id')) value="{{ old('currency_id') }}" @elseif($account != '') value="{{ $account->currency->id }}" @endif />
              </div>
            </div>
            <div class="col-lg-6 col-md-6">
              <div class="form-group" data-toggle="modal" data-target="#colorPopup">
                <label>Color</label>
                <div class="colors select-block" id="selected_color">
                  @if($account != '')
                  <label class="form-check form-check-inline form-check-label select-value">
                    <span class="color-view" style="background: {{ $account->color->code }}"></span>
                  </label>
                  @else
                  <p>Select Color</p>
                  @endif
                </div>
                <input type="hidden" id="color_id" name="color_id" @if(old('color_id')) value="{{ old('color_id') }}" @elseif($account != '') value="{{ $account->color->id }}" @endif />
              </div>
            </div>
          </div>
          <div class="form-group">
            <label for="type">Status</label>
            <div class="togglebutton" style="margin-top: 10px;">
              <label>
                <span class="exp">Hide</span>
                <input type="checkbox" id="status" data-en="1" data-dis="0" name="status" value="1" @if(old('status') == 1 || ($account != '' && $account->status == 1)) checked="checked" @endif>
                <span class="toggle"></span>
                <span class="inc">Show</span>
              </label>
            </div>
            <small>Show or Hide Account from Dashboard.</small>
          </div>

          {{ csrf_field() }}
          @if($account != '')
          <input type="hidden" name="aid" value="{{ $account->id }}" />
          @endif
          <a href="{{ url()->previous() }}" class="btn btn-primary btn-round btn-fab" role="button" title="Back"><i class="material-icons">arrow_back</i></a>
          <button type="submit" class="btn btn-primary" title="Done"><i class="material-icons">done</i></button>
          @if($account != '')
          <button type="button" rel="tooltip" title="Delete {{$account->name}} Account" class="btn btn-danger btn-round btn-fab del pull-right" del_form_id="delAcc_{{ $account->id }}">
              <i class="material-icons">delete</i>
          </button>
          @endif
        </form>
        @if($account != '')
        <form action="{{ route('account_del')}}" method="POST" id="delAcc_{{ $account->id }}">
            <input type="hidden" name="aid" value="{{ $account->id }}" />
            {{ method_field('DELETE') }}
            {{ csrf_field() }}
        </form>
        @endif
    </div>
</div>

<div class="modal fade" id="colorPopup" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="card">
        <div class="card-header card-header-primary">
          <h4 class="card-title">Colors</h4>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-md-12 text-center">
              <div class="colors select-field" data-field="#color_id" data-selected="#selected_color">
                @foreach($colors as $color)
                  <label class="form-check form-check-inline form-check-label select-value" data-value="{{ $color->id }}">
                    <span class="color-view" style="background: {{ $color->code }}"></span>
                  </label>
                @endforeach
              </div>
            </div>
          </div>
          <input type="hidden" class="btn" data-dismiss="modal" aria-label="Close" />
        </div>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="currencyPopup" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="card">
        <div class="card-header card-header-primary">
          <h4 class="card-title">Currencies</h4>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-md-12 text-center">
              <div class="icons select-field" data-field="#currency_id" data-selected="#selected_currency">
                @foreach($currencies as $currency)
                  <label class="form-check form-check-inline form-check-label select-value" data-value="{{ $currency->id }}">
                    <span class="icons-view"><i class="material-icons placeholder">{!! $currency->code !!}</i></span>
                  </label>
                @endforeach
              </div>
            </div>
          </div>
          <input type="hidden" class="btn" data-dismiss="modal" aria-label="Close" />
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
