@extends('layouts.matim')

@section('pageTitle', 'Reset Password')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-lg-4 col-md-6 ml-auto mr-auto">
            <div class="card">
                <div class="card-header card-header-primary text-center">
                    <h3 class="card-title">{{ __('Reset Password') }}</h3>
                </div>
                <div class="card-body">
                    <form class="form-horizontal" method="POST" action="{{ route('password.email') }}">
                        @csrf

                        <div class="form-group bmd-form-group @error('email') ? ' has-danger' : '' @enderror">
                            <label for="email" class="bmd-label-floating">{{ __('E-Mail Address') }}</label>
                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group bmd-form-group text-center">
                            <button type="submit" class="btn btn-primary">{{ __('Send Password Reset Link') }}</button>
                        </div>
                        <div class="text-center">
                            <a class="btn btn-sm" href="{{ route('login') }}">Login</a>
                            <a class="btn btn-sm" href="{{ route('register') }}">Register</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
