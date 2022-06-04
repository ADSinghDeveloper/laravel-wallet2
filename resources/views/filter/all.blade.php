@extends('layouts.matim')

@section('pageTitle', 'Filters')

@section('content')
<div class="card">
    <div class="card-header card-header-primary">
        <h3 class="card-title">Filters<i class="material-icons pull-right">filter_list</i></h3>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table fix-tr-height">
                <tbody>
                @forelse($filters as $filter)
                    <tr class="view-link show-actions" view-link="{{ route('filter_edit',[$filter->id])}}">
                        <td>{{ $filter->name }}</td>
                        <td>{{ $filter->sequence }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="4">No filter added yet.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
        <div class="btn-fixed">
            <a href="{{ route('filter_add')}}" class="btn btn-primary btn-round btn-fab"><i class="material-icons">add</i></a>
        </div>
    </div>
</div>
@endsection
