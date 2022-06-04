@extends('layouts.matim')

@section('pageTitle', 'Icons')

@section('content')
<div class="card">
    <div class="card-header card-header-primary">
        <h3 class="card-title">Icons<i class="material-icons pull-right">all_out</i></h3>
    </div>
    <div class="card-body table">
         @forelse($icons as $icon)
            <div class="icons td-actions show-actions">
                <span class="icons-view"><i class="material-icons">{{ $icon->code }}</i></span>
                <a href="{{ route('icon_del',[$icon->id])}}" title="Delete" alt="Delete" class="btn btn-danger btn-link btn-sm actions"><i class="material-icons">delete</i></a>
                <form action="{{ route('icon_del',[$icon->id]) }}" method="post">
                    {!! method_field('delete') !!}
                    {!! csrf_field() !!}
                    <!--<button type="submit" title="Delete" class="btn btn-primary btn-sm"></button>-->
                </form>
            </div>
        @empty
            <span>No icon added yet.</span>
        @endforelse
        <div>
        <div class="btn-fixed">
            <a href="{{ route('icon_add')}}" class="btn btn-primary btn-round btn-fab" role="button"><i class="material-icons">add</i></a>
        </div>
    </div>
    </div>
</div>
@endsection
