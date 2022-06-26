@extends('layouts.matim')

@php
  if($transaction != ''){
    $title = 'Edit';
  }else{
    $title = 'Add';
  }
@endphp

@section('pageTitle', $title . ' Transaction')

@section('content')
    <div class="card">
        <div class="card-header card-header-primary" @if($curr_account != '') style="background: {{ $curr_account->color->code }}" @endif>
            <h3 class="card-title">@if($transaction != '')<i class="material-icons pull-right">edit</i>@else<i class="material-icons pull-right">add</i>@endif {{ $title }} Transaction</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('transaction_save') }}" method="post">
              <div class="row">
                <div class="col-lg-6 col-md-6">
                  <div class="form-group bmd-form-group transac">
                    <label for="amount" class="bmd-label-floating">Amount</label>
                    <span id="typeIcon"><i class="material-icons">blur_on</i><i class="material-icons type inc">add</i><i class="material-icons type exp">remove</i></span>
                    <span id="currency"><i class="material-icons curr">@if($curr_account != ''){!! $curr_account->currency->code !!}@elseif($transaction != ''){!! $transaction->account->currency->code !!}@else{!! Helper::amountFormatting(null) !!}@endif</i></span>
                    <input type="number" name="amount" step="0.01" class="form-control" id="amount" tabindex="1" required @if(old('amount')) value="{{ old('amount') }}" @elseif($transaction != '') value="{{ $transaction->amount }}" @endif>
                  </div>
                </div>
                <div class="col-lg-6 col-md-6">
                  <div class="form-group bmd-form-group">
                    <label for="datetime" class="bmd-label-floating">Date &amp; Time</label>
                      <div class='input-group'>
                          <input type='text' name="date_time" class="form-control datetimepicker" id="datetime" tabindex="2" required @if(old('date_time')) value="{{ old('date_time') }}" @elseif($transaction != '') value="{{ date('Y/m/d g:i A',strtotime($transaction->date_time)) }}" @else value="{{ date('Y/m/d g:i A') }}" @endif />
                      </div>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-lg-6 col-md-6">
                  <div class="form-group bmd-form-group">
                    <label for="title" class="bmd-label-floating">Note</label>
                    <input name="title" class="form-control autocomplete" tabindex="3" id="title" value="@if(old('title')){{ old('title') }}@elseif($transaction != ''){{ $transaction->title }}@endif" />
                  </div>
                </div>
                <div class="col-lg-6 col-md-6">
                  <div class="bmd-form-group">
                    <label for="paymentmode">Payment Mode</label>
                    <select class="form-control" id="paymentmode" tabindex="4" name="payment_mode_id">
                      @forelse($paymentmodes as $paymentmode)
                      <option value="{{ $paymentmode->id }}" @if(old('payment_mode_id') == $paymentmode->id) selected="selected" @elseif($transaction != '' && $paymentmode->id == $transaction->paymentmode->id) selected="selected" @endif >{{ $paymentmode->name }}</option>
                      @empty
                      <option value="">Not exists</option>
                      @endforelse
                    </select>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-lg-4 col-md-4">
                  <div class="bmd-form-group">
                    <label for="type" class="pull-left">Type</label>
                    <div class="togglebutton" style="margin-left: 10px; display: inline-block;">
                      <label>
                        <span class="exp">Expense</span>
                        <input type="checkbox" id="trans-type" data-exp="1" data-inc="2" @if(old('type') == 2 || ($transaction != '' && $transaction->type == 2)) checked="checked" @endif  @if(old('type')) value="{{ old('type') }}" @elseif($transaction != '') value="{{ $transaction->type }}" @endif>
                        <span class="toggle"></span>
                        <span class="inc">Income</span>
                      </label>
                    </div>
                    <div class="hide">
                      <button type="button" class="btn btn-round btn-fab btn-sm type-btn inc @if(old('type') == 2 || ($transaction != '' && $transaction->type == 2)) active @endif" data-value="2"><i class="material-icons">add</i></button>
                      <button type="button" class="btn btn-round btn-fab btn-sm type-btn exp @if(old('type') == 1 || ($transaction != '' && $transaction->type == 1)) active @endif" data-value="1"><i class="material-icons">remove</i></button>
                    </div>
                      <input type="hidden" id="type" required @if(old('type')) value="{{ old('type') }}" @elseif($transaction != '') value="{{ $transaction->type }}" @endif name="type">
                  </div>
                </div>
                <div class="col-lg-4 col-md-4">
                  <div class="bmd-form-group" data-toggle="modal" data-target="#accountPopup">
                    <label for="account">Account</label>
                    <div class="colors select-block accounts" id="selected_account">
                      @if($transaction != '' || $curr_account != '')
                      <label class="form-check form-check-inline form-check-label select-value">
                        <span class="badge badge-primary" style="background: @if($curr_account != '') {{ $curr_account->color->code }} @elseif($transaction != '') {{ $transaction->account->color->code }} @endif">@if($curr_account != '') {{ $curr_account->name }} @elseif($transaction != '') {{ $transaction->account->name }} @endif</span>
                      </label>
                      @else
                      <p>Select Account</p>
                      @endif
                    </div>
                    <input type="text" id="account_id" name="account_id" required value="@if(old('account_id')){{ old('account_id') }}@elseif($curr_account != ''){{ $curr_account->id }}@elseif($transaction != ''){{ $transaction->account_id }}@endif" />
                  </div>
                </div>
                <div class="col-lg-4 col-md-4">
                  <div class="bmd-form-group" data-toggle="modal" data-target="#categoryPopup">
                    <label>Category</label>
                    <div class="colors select-block categories" id="selected_category">
                      @if($transaction != '')
                      <label class="form-check form-check-inline form-check-label select-value">
                        <span class="badge badge-pill" style="background: {{ $transaction->category->color->code }}"><i class="material-icons">{{ $transaction->category->icon->code }}</i> <span class="category_name">{{ $transaction->category->name }}</span></span>
                      </label>
                      @else
                      <p>Select Category</p>
                      @endif
                    </div>
                    <input type="text" id="category_id" name="category_id" required @if(old('category_id')) value="{{ old('category_id') }}" @elseif($transaction != '') value="{{ $transaction->category_id }}" @endif />
                  </div>
                </div>
              </div>
              <div class="form-row">
                <div class="form-group col-md-12">
                  {{ csrf_field() }}
                  @if($transaction != '')
                  <input type="hidden" name="tid" value="{{ $transaction->id }}" />
                  @endif
                  <a href="{{ url()->previous() }}" class="btn btn-primary btn-round btn-fab back-btn" role="button"><i class="material-icons">arrow_back</i></a>
                  <button type="submit" class="btn btn-primary"><i class="material-icons">done</i></button>
                  @if($transaction != '')
                  <button type="button" rel="tooltip" title="Delete Transaction" class="btn btn-danger btn-round btn-fab del pull-right" del_form_id="delTrasec_{{ $transaction->id }}">
                      <i class="material-icons">delete</i>
                  </button>
                  @endif
                </div>
              </div>
            </form>
            @if($transaction != '')
            <form action="{{ route('transaction_del')}}" method="POST" id="delTrasec_{{ $transaction->id }}">
                <input type="hidden" name="tid" value="{{ $transaction->id }}" />
                {{ method_field('DELETE') }}
                {{ csrf_field() }}
            </form>
            @endif
        </div>
    </div>
<script type="text/javascript">notes = {!! $notes !!};</script>
<div class="modal fade" id="categoryPopup" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="card">
        <div class="card-header card-header-primary">
          <h4 class="card-title">Categories</h4>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-md-12">
              <div class="select-field categories" data-field="#category_id" data-selected="#selected_category">
                @forelse($categories as $category)
                  <label class="form-check form-check-inline form-check-label select-value" data-value="{{ $category->id }}">
                    <span class="badge badge-pill" style="background: {{ $category->color->code }}"><i class="material-icons">{{ $category->icon->code }}</i> <span class="category_name">{{ $category->name }}</span></span>
                  </label>
                @empty
                <div class="text-center">
                  <a href="{{ route('category_add') }}" class="btn btn-primary btn-round btn-fab" title="Add Category"><i class="material-icons">add</i></a></div>
                @endforelse
              </div>
            </div>
          </div>
          <input type="hidden" class="btn" data-dismiss="modal" aria-label="Close" />
        </div>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="accountPopup" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="card">
        <div class="card-header card-header-primary">
          <h4 class="card-title">Accounts</h4>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-md-12">
              <div class="select-field accounts" data-field="#account_id" data-selected="#selected_account">
                @forelse($accounts as $account)
                  <label class="form-check form-check-inline form-check-label select-value" data-value="{{ $account->id }}">
                    <span class="badge badge-primary" style="background: {{ $account->color->code }}">{{ $account->name }}</span>
                  </label>
                @empty
                <div class="text-center">
                  <a href="{{ route('account_add') }}" class="btn btn-primary btn-round btn-fab" title="Add Account"><i class="material-icons">add</i></a></div>
                @endforelse
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
