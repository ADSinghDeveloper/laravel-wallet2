@extends('layouts.matim')

@php
  if($category != ''){
    $title = 'Edit';
  }else{
    $title = 'Add';
  }
@endphp

@section('pageTitle', $title . ' Category')

@section('content')
<div class="card">
  <div class="card-header card-header-primary">
      <h3 class="card-title">@if($category != '')<i class="material-icons pull-right">edit</i>@else<i class="material-icons pull-right">add</i>@endif {{ $title }} Category</h3>
  </div>
    <div class="card-body">
      <form action="{{ route('category_save') }}" method="post">
        <div class="row">
          <div class="col-md-4">
            <div class="form-group bmd-form-group">
              <label for="name" class="bmd-label-floating">Name</label>
              <input type="text" name="name" class="form-control" id="name" tabindex="1" required @if(old('name')) value="{{ old('name') }}" @elseif($category) value="{{ $category->name }}" @endif>
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group bmd-form-group">
              <label for="desc" class="bmd-label-floating">Description</label>
              <input type="text" name="description" class="form-control" id="desc" tabindex="2" @if(old('description')) value="{{ old('description') }}" @elseif($category) value="{{ $category->description }}" @endif>
            </div>
          </div>
          <div class="col-lg-4 col-md-4">
            <div class="form-group bmd-form-group">
              <label for="sequence" class="bmd-label-floating">Sequence</label>
              <input type="number" name="sequence" class="form-control" id="sequence" tabindex="3" @if(old('sequence')) value="{{ old('sequence') }}" @elseif($category) value="{{ $category->sequence }}" @endif>
            </div>
          </div>
        </div>
        <div class="row">
        <div class="form-group bmd-form-group col-sm-6 col-md-6 col-lg-6" data-toggle="modal" data-target="#colorPopup">
          <label>Color</label>
          <div class="colors select-block" id="selected_color">
            @if($category != '')
            <label class="form-check form-check-inline form-check-label select-value">
              <span class="color-view" style="background: {{ $category->color->code }}"></span>
            </label>
            @else
            <p>Select Color</p>
            @endif
          </div>
          <input type="hidden" id="color_id" name="color_id" @if(old('color_id')) value="{{ old('color_id') }}" @elseif($category != '') value="{{ $category->color->id }}" @endif />
        </div>
        <div class="form-group bmd-form-group col-sm-6 col-md-6 col-lg-6" data-toggle="modal" data-target="#iconPopup">
          <label>Icon</label>
          <div class="icons select-block" id="selected_icon">
            @if($category != '')
            <label class="form-check form-check-inline form-check-label select-value" title="{{ $category->icon->code }}">
              <span class="icons-view"><i class="material-icons">{{ $category->icon->code }}</i></span>
            </label>
            @else
            <p>Select Icon</p>
            @endif
          </div>
          <input type="hidden" id="icon_id" name="icon_id" @if(old('icon_id')) value="{{ old('icon_id') }}" @elseif($category != '') value="{{ $category->icon->id }}" @endif />
        </div>
        </div>
        {{ csrf_field() }}
        @if($category != '')
        <input type="hidden" name="cid" value="{{ $category->id }}" />
        @endif
        <div>
          <a href="{{ route('categories')}}" class="btn btn-primary btn-round btn-fab" role="button" title="Back"><i class="material-icons">arrow_back</i></a>
          <button type="submit" class="btn btn-primary" title="Done"><i class="material-icons">done</i></button>
          @if($category != '')
          <button type="button" rel="tooltip" title="Delete {{$category->name}} Category" class="btn btn-danger btn-round btn-fab del pull-right" del_form_id="delCat_{{ $category->id }}">
              <i class="material-icons">delete</i>
          </button>
          @endif
        </div>
      </form>
      @if($category != '')
      <form action="{{ route('category_del')}}" method="POST" id="delCat_{{ $category->id }}">
          <input type="hidden" name="cid" value="{{ $category->id }}" />
          {{ method_field('DELETE') }}
          {{ csrf_field() }}
      </form>
      @endif
    </div>
</div>

<div class="modal fade" id="colorPopup" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="card">
        <div class="card-header card-header-primary">
          <h4 class="card-title">Colors</h4>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-md-12 text-center">
              <div class="colors select-field" data-field="#color_id" data-selected="#selected_color">
                @foreach($colors as $color)
                  <label class="form-check form-check-inline form-check-label select-value" data-value="{{ $color->id }}">
                    <span class="color-view" style="background: {{ $color->code }}"></span>
                  </label>
                @endforeach
              </div>
            </div>
          </div>
          <input type="hidden" class="btn" data-dismiss="modal" aria-label="Close" />
        </div>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="iconPopup" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="card">
        <div class="card-header card-header-primary">
          <h4 class="card-title">Icons</h4>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-md-12 text-center">
              <div class="icons select-field" data-field="#icon_id" data-selected="#selected_icon">
              @foreach($icons as $icon)
                <label class="form-check form-check-inline form-check-label select-value @if($category != '' && $category->icon->id == $icon->id ) active @endif" data-value="{{ $icon->id }}">
                  <span class="icons-view"><i class="material-icons">{{ $icon->code }}</i></span>
                </label>
              @endforeach
              </div>
            </div>
          </div>
          <input type="hidden" class="btn" data-dismiss="modal" aria-label="Close" />
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
