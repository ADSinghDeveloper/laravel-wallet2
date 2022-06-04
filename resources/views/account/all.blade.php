@extends('layouts.matim')

@section('pageTitle', 'Accounts')

@section('content')
<div class="card">
    <div class="card-header card-header-primary">
        <h3 class="card-title">Accounts<i class="material-icons pull-right">person</i></h3>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table">
                <!--<thead class=" text-primary">
                    <tr>
                        <th></th>
                        <th>Name</th>
                        <th>Amount</th>
                        <th class="text-right">Action</th>
                    </tr>
                </thead>-->
                <tbody>
                @forelse($accounts as $account)
                    <tr class="view-link show-actions" view-link="{{ route('account_edit',[$account->id])}}">
                        <td>{{ $account->name }} @if($account->status == 0)<small>(Hidden)</small>@endif</td>
                        <td class="@if($account->amount < 0)neg @endif">{!! Helper::amountFormatting($account->amount,$account->currency->code) !!}</td>
                        <td class="td-actions">
                            <a href="{{ route('transaction_view',[$account->id])}}" title="View {{$account->name}} Transactions" class="btn btn-primary btn-link btn-sm">
                                <i class="material-icons">view_list</i>
                            </a>
                        </td>
                        <td>{{ $account->sequence }}</td>
                        <td class="text-right">
                            <span class="color-view" style="background: {{ $account->color->code }}"></span>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="3">No account added yet.</td></tr>
                @endforelse
                </tbody>
            </table>
            <small><a href="{{ route('balance_update') }}">Reset Accounts Balance</a></small>
        </div>
        <div class="btn-fixed">
            <a href="{{ route('account_add')}}" class="btn btn-primary btn-round btn-fab"><i class="material-icons">add</i></a>
        </div>
    </div>
</div>

@endsection
