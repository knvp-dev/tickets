@extends('layouts.fullscreen')

@section('content')
<div class="fullscreen">
    <div class="container">
        {{-- <div class="brand-icon">
            <img style="width:100px;" src="/images/T.svg" alt="">
        </div> --}}
    </div>

    <div class="container">
        <div class="login-form floating-panel has-text-centered">
            <h1 class="title has-text-centered is-uppercase is-text-blue">Login</h1>
            <form class="form-horizontal" role="form" method="POST" action="{{ url('/login') }}">
                {{ csrf_field() }}
                <p class="control">
                    <input class="input" type="email" name="email" value="{{ old('email') }}" placeholder="Email address" required autofocus>
                    <span class="help is-danger">{{ $errors->first('email') }}</span>
                </p>
                <p class="control">
                    <input class="input" type="password" name="password" placeholder="Wachtwoord" required>
                    <span class="help is-danger">{{ $errors->first('password') }}</span>
                </p>

                <a class="small-link" href="/auth/password">Forgot your password?</a>

                <p class="control">
                    <button class="login-submit button blue-button" type="submit">Login</button>
                </p>

                <p>Don't have an account? <a href="/register">Sign up!</a></p>
            </form>
        </div>
    </div>
</div>
@endsection
