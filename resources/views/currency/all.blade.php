@extends('layouts.matim')

@section('pageTitle', 'Currencies')

@section('content')
<div class="card">
    <div class="card-header card-header-primary">
        <h3 class="card-title">Currencies<i class="material-icons pull-right">attach_money</i></h3>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table fix-tr-height">
                <tbody>
                @forelse($currencies as $currency)
                    <tr class="view-link show-actions" view-link="{{ route('currency_edit',[$currency->id])}}">
                        <td>{{ $currency->name }}</td>
                        <td><span class="curr">{!! $currency->code !!}</span></td>
                    </tr>
                    @empty
                    <tr><td colspan="4">No currency added yet.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
        <div class="btn-fixed">
            <a href="{{ route('currency_add')}}" class="btn btn-primary btn-round btn-fab"><i class="material-icons">add</i></a>
        </div>
    </div>
</div>

@endsection
