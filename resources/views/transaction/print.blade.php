@extends('layouts.print')

@section('pageTitle', $account->name . ' Transactions')

@section('content')

@php
    $balAmt = $account->amount;
    $lastDate = date('Ym',strtotime('+1 month'));
@endphp
<div class="card">
    <div class="card-header card-header-primary" @if($account) style="background: {{ $account->color->code }};" @endif>
        <h3 class="card-title">@if($selectedFilter){{ $selectedFilter->name }}@else{{ Helper::removeSpecialChars($account->name) }}@endif<span class="pull-right">{!! Helper::amountFormatting( $account->amount,$account->currency->code) !!}</span></h3>
    </div>
    <div class="card-body">
        <div class="row padding05">
            <div class="col-lg-12">
                <div class="filter-block filter">
                @if($filteredCats || $filteredMods || $type || $search || $from || $till || $selectedFilter)
                    <span class="hide">
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
                    </span>
                    <div class="d-inline" data-toggle="modal" data-target="#filterPopup">
                        @if($from || $till)<small class="badge simple">{{ $from }} - {{ $till }}</small>@endif
                        @if($search)<small class="badge simple">"{{ $search }}"</small>@endif
                    </div>
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
                        <td colspan="5" class="text-2">{{ date('M, Y',strtotime($transaction->date_time)) }}</td>
                    </tr>
                        @php
                            $lastDate = date('Ym',strtotime($transaction->date_time));
                            $printFlag = false;
                        @endphp
                    @endif
                    <tr class="view-link show-actions" view-link="{{ route('transaction_edit',[$transaction->id])}}">
                        <td class="text-center categories"><span class="color-view" style="background: {{ $transaction->category->color->code }}">{{ strtoupper(substr(Helper::removeSpecialChars($transaction->category->name),0,1)) }}</span>
                        </td>
                        <td class="transaction_name">{{ $transaction->category->name }}@if($transaction->title)<div class="note"><small>"{{ $transaction->title }}"</small></div>@endif
                        </td>
                        @if($account->id == '')
                        <td>
                            <div class="account_name"><span class="acc" style="color: {{ $transaction->account->color->code }}">{{ $transaction->account->name }}</span></div>
                        </td>
                        @endif
                        <td class="payment-col" width="200">
                            <div class="payment-note"><small>{{ $transaction->paymentmode->name }}</small></div>
                        </td>
                        <td class="td-actions text-right">
                            <div class="transc_amt amount @if($transaction->type == 2) inc @endif @if($transaction->type == 1) exp @endif">{!! Helper::amountFormatting($transaction->amount, $transaction->account->currency->code) !!}</div>
                            <div>
                                @php
                                    $balAmt -= $transaction->amount;
                                    $grossBalance -= $transaction->amount;
                                @endphp
                                <small class="amount @if($balAmt >= 0) inc @endif @if($balAmt < 0) exp @endif">({!! Helper::amountFormatting($balAmt,$transaction->account->currency->code) !!})</small>
                            </div>
                            <div><small class="trans-date">{{ Helper::formatDate($transaction->date_time,'j M, Y') }}</small></div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="3">No transaction in this period.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
  </div>
</div>
@endsection
