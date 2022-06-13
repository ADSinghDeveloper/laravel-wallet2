@extends('layouts.matim')

@section('pageTitle', $account->name . ' Transactions')

@section('content')

@php
    $balAmt = $account->amount;
    $lastDate = date('Ym',strtotime('+1 month'));
    $searchPrintFlag = false;
@endphp
<div class="card">
    <div class="card-header card-header-primary" @if($account) style="background: {{ $account->color->code }};" @endif>
        <h3 class="card-title">{{ $account->name }}<span class="pull-right">{!! Helper::amountFormatting( $account->amount,$account->currency->code) !!}</span></h3>
    </div>
    <div class="card-body">
        <div class="row padding05">
            <div class="col-lg-12">
                <div class="filter-block filter">
                @if($filteredCats || $filteredMods || $type || $search || $from || $till || $selectedFilter)
                    @if($selectedFilter)<a href="#" data-toggle="modal" data-target="#viewFilter" title="View {{ $selectedFilter->name }} Filter"><small class="badge badge-pill badge-dark filter-badge"><i class="material-icons">filter_list</i> {{ $selectedFilter->name }}</small></a>
                    @elseif($filteredCats || $filteredMods || $type)
                    <span class="instantFilter">
                        <a href="#" data-toggle="modal" data-target="#filterPopup"><small class="badge badge-pill badge-dark filter-badge">Instant Filter</small></a>
                        <a href="#" id="saveFilterBtn" data-toggle="modal" data-target="#saveFilter"><small class="badge simple"><i class="material-icons">save</i></small></a>
                    </span>
                    @endif
                    <span id="forViewFilter" class="hide">
                    @forelse($filteredCats as $cat)
                        <span class="badge badge-pill badge-primary" style="background: {{ $cat->color->code }}">{{ $cat->name }}</span>
                    @empty
                    @endforelse
                    @forelse($filteredMods as $mod)
                        <span class="badge mod">{{ $mod->name }}</span>
                    @empty
                    @endforelse
                    @if($type)
                        @if($type == 2)
                        <span class="badge badge-pill simple inc">Income(s)</span>
                        @else
                        <span class="badge badge-pill simple exp">Expence(s)</span>
                        @endif
                    @endif
                    @if($selectedFilter && $selectedFilter->search != '')
                        <small class="badge simple">"{{ $search }}"</small>
                        @php $searchPrintFlag = true; @endphp
                    @endif
                    </span>
                    <div class="d-inline filterPopup" data-toggle="modal" data-target="#filterPopup">
                        @if($from || $till)<small class="badge simple">{{ $from }} - {{ $till }}</small>@endif
                        @if($search && !$searchPrintFlag)<small class="badge simple">"{{ $search }}"</small>@endif
                    </div>
                    @if(count($transactions) > 0)
                    <a href="#" class="badge badge-dark filter-badge" alt="Print PDF" title="Print PDF" id="print_pdf_btn" style="background-color: #d93025;"><i class="material-icons">picture_as_pdf</i></a>
                    <a href="#" class="badge badge-dark filter-badge" alt="Export CSV" title="Export CSV" id="export_csv_btn"><i class="material-icons">download</i></a>
                    @endif
                    @if($filteredCats || $filteredMods || $type || $search || $selectedFilter)
                    <a href="{{ route('transaction_view',array_merge([($account->id != ''? $account->id : '')],(old('all_trans')?['all_trans' => old('all_trans')]: []),(!old('all_trans') && ($from || $till)?['from' => $from, 'till' => $till] : []))) }}" class="pull-right close"><span class="close"><i class="material-icons">close</i></span></a>
                    @endif
                @endif
                    <small class="badge simple pull-right">{{ $minRecords }} {{ $totalRecords }} record(s)</small>
                </div>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table transactions">
                <tbody>
                @forelse($transactions as $transaction)
                    @if(date('Ym',strtotime($transaction->date_time)) < $lastDate)
                    <tr>
                        <td colspan="5" class="text-2">{{ date('M, Y',strtotime($transaction->date_time)) }} <small>Balance {!! Helper::amountFormatting($grossBalance,$account->currency->code) !!}</small>
                        </td>
                    </tr>
                        @php
                            $lastDate = date('Ym',strtotime($transaction->date_time));
                            $printFlag = false;
                        @endphp
                    @endif
                    <tr class="view-link show-actions" view-link="{{ route('transaction_edit',[$transaction->id])}}">
                        <td class="text-center categories"><span class="color-view" style="background: {{ $transaction->category->color->code }}"><i class="material-icons">{{ $transaction->category->icon->code }}</i></span>
                        </td>
                        <td class="transaction_name">{{ $transaction->category->name }}
                        @if($account->id == '')
                            <div class="account_name"><span class="acc" style="color: {{ $transaction->account->color->code }}">{{ $transaction->account->name }}</span></div>
                        @endif
                        @if($transaction->title)<div class="note"><small>"{{ $transaction->title }}"</small></div>@endif
                        </td>
                        <td class="payment-col">
                            <div class="payment-note"><small><i class="material-icons">payment</i>{{ $transaction->paymentmode->name }}</small></div>
                        </td>
                        <td class="td-actions text-right">
                            <div class="transc_amt amount text-nowrap @if($transaction->amount >= 0) inc @else exp @endif">{!! Helper::amountFormatting($transaction->amount, $transaction->account->currency->code) !!}</div>
                            <div>
                                @php
                                    $balAmt -= $transaction->amount;
                                    $grossBalance -= $transaction->amount;
                                @endphp
                                <small class="amount @if($balAmt >= 0) inc @endif @if($balAmt < 0) exp @endif">({!! Helper::amountFormatting($grossBalance,$transaction->account->currency->code) !!})</small>
                            </div>
                            <div><small class="trans-date">{{ Helper::formatDate($transaction->date_time,'j M, Y') }}</small></div>
                        </td>
                    </tr>
                @empty
                    <tr><td style="padding: 15px 0">No transaction in this period.</td></tr>
                @endforelse
                    <tr>
                        <td colspan="5" class="text-2"><small>Last Balance {!! Helper::amountFormatting($grossBalance,$account->currency->code) !!}</small>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="btn-fixed">
            <a href="{{ route('transaction_add',($account->id > 0? ['aid' => $account->id] : []))}}" class="btn btn-primary btn-round btn-fab" @if($account) style="background: {{ $account->color->code }}" @endif role="button"><i class="material-icons">add</i></a>
            <a href="#" class="btn btn-primary btn-round btn-fab" role="button" data-toggle="modal" data-target="#filterPopup"><i class="material-icons">filter_list</i></a>
        </div>
  </div>
</div>
@if($selectedFilter)
<div class="modal fade" id="viewFilter" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="card">
        <div class="card-header card-header-primary">
          <h4 class="card-title">View Filter<i class="material-icons pull-right">filter_list</i></h4>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-lg-12">
                    <h3>{{ $selectedFilter->name }}</h3>
                    <div class="filter"></div>
                    <div class="form-row">
                        <div class="col-md-12">
                            <a href="{{ route('filter_edit',$selectedFilter->id) }}" title="Edit {{ $selectedFilter->name }} filter" class="btn btn-primary btn-round btn-fab"><i class="material-icons">edit</i></a>
                            <button type="button" class="btn btn-primary btn-round btn-fab pull-right" data-dismiss="modal" aria-label="Close"><i class="material-icons">close</i></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endif
<div class="modal fade" id="saveFilter" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="card">
        <div class="card-header card-header-primary">
          <h4 class="card-title">Save Filter</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('filter_save') }}" method="post">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="form-group bmd-form-group">
                            <label for="name" class="bmd-label-floating">Name</label>
                            <input type="text" name="name" class="form-control" id="name" tabindex="1" required @if(old('name')) value="{{ old('name') }}" @endif>
                        </div>
                    </div>
                </div>
                <br>
                <div class="filter"></div>
                @if(is_array(old('cids')))
                    @foreach(old('cids') as $cids)
                <input type="hidden" name="cids[]" value="{{ $cids }}">
                    @endforeach
                @endif
                @if(is_array(old('mods')))
                    @foreach(old('mods') as $mods)
                <input type="hidden" name="mods[]" value="{{ $mods }}">
                    @endforeach
                @endif
                @if(old('type'))
                <input type="hidden" name="type" value="{{ old('type') }}">
                @endif

                <input type="hidden" name="from_transac" value="1" />
                <input type="hidden" name="aid" value="{{ $account->id > 0 ? $account->id : '' }}" >
                <input type="hidden" name="from" value="{{ $from }}" >
                <input type="hidden" name="till" value="{{ $till }}" >
                <input type="hidden" name="search" value="{{ old('search') }}" >

                <div class="form-row">
                    <div class="form-group col-md-12">
                        {{ csrf_field() }}
                        <button type="submit" class="btn btn-primary"><i class="material-icons">done</i></button>
                        <button type="button" class="btn btn-primary btn-round btn-fab pull-right" data-dismiss="modal" aria-label="Close"><i class="material-icons">close</i></button>
                    </div>
                </div>
            </form>
        </div>
      </div>
    </div>
  </div>
</div>
<form class="hide" id="trans_action" method="post" target="_blank" action="{{ route('transactions_export') }}">
    <input type="hidden" name="tids" value="{{ $transactions->pluck('id')->implode(',') }}">
    @csrf
</form>
<form class="hide" id="print_pdf">
    @if(is_array(old('cids')))
        @foreach(old('cids') as $cids)
    <input type="hidden" name="cids[]" value="{{ $cids }}">
        @endforeach
    @endif
    @if(is_array(old('mods')))
        @foreach(old('mods') as $mods)
    <input type="hidden" name="mods[]" value="{{ $mods }}">
        @endforeach
    @endif
    @if(is_array(old('aids')))
        @foreach(old('aids') as $aids)
    <input type="hidden" name="aids[]" value="{{ $aids }}">
        @endforeach
    @endif
    @if(old('type'))
    <input type="hidden" name="type" value="{{ old('type') }}">
    @endif

    <input type="hidden" name="filter" value="{{ old('filter') }}" />
    <input type="hidden" name="from" value="{{ $from }}" >
    <input type="hidden" name="till" value="{{ $till }}" >
    <input type="hidden" name="search" value="{{ old('search') }}" >
    <input type="hidden" name="pdf" value="1" >
    {{ csrf_field() }}
</form>
@include("transaction.filter")
@endsection
