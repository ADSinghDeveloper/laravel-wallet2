@extends('layouts.matim')

@section('pageTitle', 'Colors')

@section('content')
<div class="card">
    <div class="card-header card-header-primary">
        <h3 class="card-title">Colors<i class="material-icons pull-right">format_color_fill</i></h3>
    </div>
    <div class="card-body table">
         @forelse($colors as $color)
            <div class="colors td-actions show-actions">
                <span class="color-view" style="background: {{ strtoupper($color->code) }}"></span>
                <a href="{{ route('color_del',[$color->id])}}" title="Delete" alt="Delete" class="btn btn-danger btn-link btn-sm actions"><i class="material-icons">delete</i></a>
                <form action="{{ route('color_del',[$color->id]) }}" method="post">
                    {!! method_field('delete') !!}
                    {!! csrf_field() !!}
                    <!--<button type="submit" title="Delete" class="btn btn-primary btn-sm"></button>-->
                </form>
            </div>
        @empty
            <span>No color added yet.</span>
        @endforelse
        <div>
        <div class="btn-fixed">
            <a href="{{ route('color_add')}}" class="btn btn-primary btn-round btn-fab" role="button"><i class="material-icons">add</i></a>
        </div>
    </div>
    </div>
</div>
@endsection
