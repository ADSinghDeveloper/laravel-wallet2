@extends('layouts.matim')

@section('pageTitle', 'Categories')

@section('content')
<div class="card">
    <div class="card-header card-header-primary">
        <h3 class="card-title">Categories<i class="material-icons pull-right">category</i></h3>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table categories">
                <tbody>
                @forelse($categories as $category)
                    <tr class="view-link show-actions" view-link="{{ route('category_edit',[$category->id])}}">
                        <td>{{ $category->name }}
                            @if($category->description)
                                <div class="note"><small>"{{ $category->description }}"</small></div>
                            @endif
                        </td>
                        <td>{{ $category->sequence }}</td>
                        <td class="td-actions text-right">
                            <span class="color-view" style="background: {{ $category->color->code }}"><i class="material-icons">{{ $category->icon->code }}</i></span>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="4">No category added yet.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
        <div class="btn-fixed">
            <a href="{{ route('category_add')}}" class="btn btn-primary btn-round btn-fab"><i class="material-icons">add</i></a>
        </div>
    </div>
</div>
@endsection
