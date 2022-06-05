@extends('layouts.matim')

@section('pageTitle', 'Register')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-4 col-md-6 ml-auto mr-auto">
            <div class="card">
                <div class="card-header card-header-primary text-center">
                    <h3 class="card-title">{{ __('Register') }}</h3>
                </div>
                <div class="card-body">
                    <form class="form-horizontal" method="POST" action="{{ route('register') }}">
                        @csrf

                        <div class="form-group bmd-form-group @error('name') ? ' has-danger' : '' @enderror">
                            <label for="name" class="bmd-label-floating">{{ __('Name') }}</label>
                            <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>

                            @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group bmd-form-group @error('email') ? ' has-danger' : '' @enderror">
                            <label for="email" class="bmd-label-floating">{{ __('E-Mail Address') }}</label>
                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">

                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group bmd-form-group @error('password') ? ' has-danger' : '' @enderror">
                            <label for="password" class="bmd-label-floating">{{ __('Password') }}</label>
                            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">

                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group bmd-form-group">
                            <label for="password-confirm" class="bmd-label-floating">{{ __('Confirm Password') }}</label>
                            <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                        </div>
                        <div class="form-group bmd-form-group text-center">
                            <button type="submit" class="btn btn-primary">{{ __('Register') }}</button>
                        </div>
                    </form>
                    <div class="text-center">
                        <a class="btn btn-sm" href="{{ route('login') }}">Login</a>
                        @if (Route::has('password.request'))
                            <a class="btn btn-sm" href="{{ route('password.request') }}">
                                {{ __('Forgot Your Password?') }}
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
