@extends('layouts.matim')

@section('pageTitle', 'Login')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-lg-4 col-md-6 col-sm-8 ml-auto mr-auto">
            <div class="card">
                <div class="card-header card-header-primary text-center">
                    <h3 class="card-title">{{ __('Login') }}</h3>
                </div>
                <div class="card-body">
                    <form class="form-horizontal" method="POST" action="{{ route('login') }}">
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

                        <div class="form-group bmd-form-group @error('password') ? ' has-danger' : '' @enderror">
                            <label for="password" class="bmd-label-floating">{{ __('Password') }}</label>
                            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-check bmd-form-group">
                            <label class="form-check-label" for="remember">
                            <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>{{ __('Remember Me') }}
                            <span class="form-check-sign">
                                <span class="check"></span>
                            </span>
                            </label>
                        </div>
                        <div class="form-check bmd-form-group text-center">
                            <button type="submit" class="btn btn-primary">{{ __('Login') }}</button>
                        </div>
                    </form>
                    <div class="text-center">
                        <a class="btn btn-sm" href="{{ route('register') }}">Register</a>
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
