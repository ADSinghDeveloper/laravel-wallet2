@extends('layouts.matim')

@section('pageTitle', 'Dashboard')

@section('content')
<div class="container-fluid">
<div class="row block">
    <div class="col-lg-12">
        <h3 class="title"><a href="{{ route('accounts') }}">Accounts</a><a href="{{ route('account_add')}}" class="pull-right" role="button"><i class="material-icons">add</i></a></h3>
    </div>
    @forelse($accounts as $account)
    <div class="col-lg-4 col-md-4 col-sm-6 editable">
        <a href="{{ route('account_edit',[$account->id])}}" title="Edit {{ $account->name }} account" class="edit"><span class="badge simple" style="color: {{ $account->color->code }};"><i class="material-icons pull-right">edit</i></span></a>
        <a href="{{ route('transaction_view',[$account->id])}}" title="View {{ $account->name }} Account">
        <div class="card text-center">
            <div class="card-header card-header-primary" style="background: {{ $account->color->code }};">
                <h3 class="card-title">{{ $account->name }}</h3>
            </div>
            <div class="card-body text-center">
                <div class="total-amount @if($account->amount < 0) neg @endif">
                    {!! Helper::amountFormatting($account->amount,$account->currency->code) !!}
                </div>
            </div>
        </div>
        </a>
    </div>
    @empty
    <div class="col-lg-12">
        <p>No account to show here.</p>
    </div>
    @endforelse
</div>
<div class="row block">
    <div class="col-lg-12">
        <h3 class="title"><a href="{{ route('filters') }}">Filters<a href="{{ route('filter_add')}}" class="pull-right" role="button"><i class="material-icons">add</i></a></h3>
    </div>
    @forelse($dashboardFilters as $fid => $dashboardFilter)
        @foreach($dashboardFilter as $aid => $filter)
    <div class="col-lg-3 col-md-4 col-sm-6 editable">
        <a href="{{ route('filter_edit',[$fid])}}" title="Edit {{ $filter['filterName'] }} filter" class="edit"><span class="badge simple" style="color: {{ $filter['accColor'] }};"><i class="material-icons pull-right">edit</i></span></a>
        <a href="{{ route('transaction_view',[$aid, 'filter' => $fid])}}" title="View {{ $filter['filterName'] }} filter on {{ $filter['accName'] }} account">
        <div class="card card-filter text-center">
            <div class="card-header card-header-primary" style="background: {{ $filter['accColor'] }};">
                <h4 class="card-title text-left">{{ $filter['filterName'] }}<i class="material-icons pull-right">filter_list</i></h4>
            </div>
            <div class="card-body text-center">
                <div class="total-amount @if($filter['filterAmount'] < 0)neg @endif">{!! Helper::amountFormatting($filter['filterAmount'],$filter['accCurr']) !!}</div>
            </div>
            <div class="card-footer" style="display: none;">
                <span class="stats" style="color: {{ $filter['accColor'] }}; text-transform: uppercase;">{{ $filter['accName'] }}</span>
            </div>
        </div>
        </a>
    </div>
        @endforeach
    @empty
    <div class="col-lg-12">
        <p>No filter to show here.</p>
    </div>
    @endforelse
</div>
<div class="row block">
    <div class="col-lg-12">
        <h3 class="title">Balance by Currency</h3>
    </div>
    @forelse($currencyAmount as $key => $currAmt)
    <div class="col-lg-4 col-md-4 col-sm-6">
        <a href="{{ route('transaction_view',['', 'active_accounts_only' => 1])}}" title="View All">
        <div class="card card-stats text-center">
            <div class="card-header card-header-primary">
                <h3 class="card-title">{{ $currAmt->name }}</h3>
            </div>
            <div class="card-body text-center">
                <div class="total-amount">
                    {!! Helper::amountFormatting($currAmt->amount,$currAmt->code) !!}
                </div>
            </div>
        </div>
        </a>
    </div>
    @empty
    <div class="col-lg-12">
        <p>No currency to show here.</p>
    </div>
    @endforelse
</div>
<div class="row block">
    <div class="col-lg-12">
        <h3 class="title"><a href="{{ route('transaction_view') }}">Transactions</a> <small>(Last 5)</small><a href="{{ route('transaction_add')}}" class="pull-right" role="button"><i class="material-icons">add</i></a></h3>
    </div>
    <div class="table-responsive pl-3 pr-3">
        <table class="table transactions">
            <tbody>
@php
    $balAmt = @($account) ? $account->amount : 0;
    $lastDate = date('Ym',strtotime('+1 month'));
@endphp
            @forelse($transactions as $transaction)
                @if(date('Ym',strtotime($transaction->date_time)) < $lastDate)
                    @php
                        $lastDate = date('Ym',strtotime($transaction->date_time));
                        $printFlag = false;
                    @endphp
                @endif
                <tr class="view-link show-actions" view-link="{{ route('transaction_edit',[$transaction->id])}}">
                    <td class="text-center categories"><span class="color-view" style="background: {{ $transaction->category->color->code }}"><i class="material-icons">{{ $transaction->category->icon->code }}</i></span>
                    </td>
                    <td class="transaction_name">{{ $transaction->category->name }}
                        <div class="account_name"><span class="acc" style="color: {{ $transaction->account->color->code }}">{{ $transaction->account->name }}</span></div>
                    @if($transaction->title)<div class="note"><small>{{ $transaction->title }}</small></div>@endif
                    </td>
                    <td class="payment-col">
                        <div class="payment-note"><small><i class="material-icons">payment</i>{{ $transaction->paymentmode->name }}</small></div>
                    </td>
                    <td class="td-actions text-right">
                        <div class="transc_amt amount @if($transaction->type == 2) inc @endif @if($transaction->type == 1) exp @endif">{!! Helper::amountFormatting($transaction->amount, $transaction->account->currency->code) !!}</div>
                        <div>
                            @php
                                $balAmt -= $transaction->amount;
                            @endphp
                            <small class="amount @if($balAmt >= 0) inc @endif @if($balAmt < 0) exp @endif"></small>
                        </div>
                        <div><small class="trans-date">{{ Helper::formatDate($transaction->date_time,'j M, Y') }}</small></div>
                    </td>
                </tr>
            @empty
                <tr><td style="padding: 15px 0">No transaction to show here.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
</div>
<div class="btn-fixed hide">
    <a href="{{ route('transaction_add')}}" class="btn btn-primary btn-round btn-fab" role="button"><i class="material-icons">add</i></a>
</div>
@endsection
