@extends('layouts.matim')

@section('pageTitle', 'Payment Modes')

@section('content')
<div class="card">
    <div class="card-header card-header-primary">
        <h3 class="card-title">Payment Modes<i class="material-icons pull-right">payment</i></h3>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table fix-tr-height">
                <tbody>
                @forelse($paymentmodes as $paymentmode)
                    <tr class="view-link show-actions" view-link="{{ route('paymentmode_edit',[$paymentmode->id])}}">
                        <td>{{ $paymentmode->name }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="2">No payment mode added yet.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
        <div class="btn-fixed">
            <a href="{{ route('paymentmode_add')}}" class="btn btn-primary btn-round btn-fab"><i class="material-icons">add</i></a>
        </div>
    </div>
</div>
@endsection
