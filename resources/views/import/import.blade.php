@extends('layouts.matim')

@section('pageTitle', 'Import')

@section('content')
<div class="card">
    <div class="card-header card-header-primary">
        <h3 class="card-title">Import<i class="material-icons pull-right">get_app</i></h3>
    </div>
    <div class="card-body">
        <form action="{{ route('import_save') }}" id="import_save" method="post" enctype="multipart/form-data">
          <div class="form-group bmd-form-group delimeter">
            <label for="name" class="bmd-label-floating">Separator</label>
            <input type="text" name="delimeter" class="form-control" id="delimeter" required value=",">
            <small class="form-text text-muted">Leave blank to use default comma (,) seperator for CSV file.</small>
          </div>
          <div class="form-group">
            <div class="fileinput fileinput-new" data-provides="fileinput">
              <label for="amount">CSV File</label>
              <span class="fileinput-preview fileinput-exists thumbnail"></span>
              <button href="#" class="btn btn-danger btn-round btn-fab fileinput-exists" data-dismiss="fileinput"><i class="material-icons">close</i></button>
              <span class="btn btn-raised btn-round btn-default btn-file">
                  <span class="fileinput-new">Select file</span>
                  <span class="fileinput-exists">Change</span>
                  <input type="file" name="csv_file" id="csv_file" required />
              </span>
            </div>
          </div>
          {{ csrf_field() }}
          <div class="form-group">
            <a href="{{ route('transaction_view')}}" class="btn btn-primary btn-round btn-fab" role="button"><i class="material-icons">arrow_back</i></a>
            <button type="submit" class="btn btn-primary"><i class="material-icons">done</i></button>
          </div>
          <div class="form-group"><small><a href="{{ url('wallet_import_sample.csv') }}">Click here</a> to get sample of CSV import file.</small></div>
      </form>
    </div>
</div>
@endsection
