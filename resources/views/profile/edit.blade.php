@extends('layouts.matim')

@section('pageTitle', 'Update Profile')

@section('content')

<div class="card">
  	<div class="card-header card-header-primary">
      <h3 class="card-title">Update Profile</h3>
  	</div>
    <div class="card-body">
      <form action="{{ route('profile_save') }}" method="post">
  		<div class="row">
        <div class="col-lg-6 col-md-6 col-sm-12">
		    	<div class="form-group bmd-form-group">
	            <label for="name" class="bmd-label-floating">Name</label>
	            <input type="text" name="name" class="form-control" id="name" required @if(old('name')) value="{{ old('name') }}" @else value="{{ $profile->name }}" @endif>
	        </div>
		    </div>
		    <div class="col-lg-6 col-md-6 col-sm-12">
		    	<div class="form-group bmd-form-group">
		          	<label for="email" class="bmd-label-floating">Email</label>
		          	<input type="text" name="email" class="form-control" id="email" required @if(old('email')) value="{{ old('email') }}" @else value="{{ $profile->email }}" @endif disabled="disabled">
		        </div>
		    </div>
		  </div>
	    <div class="row">
		    <div class="col-lg-4 col-md-4 col-sm-12">
		    	<div class="form-group bmd-form-group">
		          	<label for="curr_psw" class="bmd-label-floating">Current Password</label>
		          	<input type="password" name="curr_psw" class="form-control" id="curr_psw">
		        </div>
		    </div>
		    <div class="col-lg-4 col-md-4 col-sm-12">
		    	<div class="form-group bmd-form-group">
		          	<label for="new_psw" class="bmd-label-floating">New Password</label>
		          	<input type="password" name="new_psw" class="form-control" id="new_psw">
		        </div>
		    </div>
		    <div class="col-lg-4 col-md-4 col-sm-12">
		    	<div class="form-group bmd-form-group">
		          	<label for="conf_psw" class="bmd-label-floating">Confirm Password</label>
		          	<input type="password" name="conf_psw" class="form-control" id="conf_psw">
		        </div>
		    </div>
	    </div>
        {{ csrf_field() }}
        <div class="form-group">
          <a href="{{ url()->previous() }}" class="btn btn-primary btn-round btn-fab" role="button" title="Back"><i class="material-icons">arrow_back</i></a>
          <button type="submit" class="btn btn-primary"><i class="material-icons">done</i></button>
        </div>
      </form>
    </div>
</div>
@endsection
