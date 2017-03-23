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
            <h1 class="title has-text-centered is-uppercase is-text-blue">Register</h1>
            <form class="form-horizontal" role="form" method="POST" action="{{ url('/register') }}">
                {{ csrf_field() }}
                <p class="control">
                    <input class="input" type="text" name="name" value="{{ old('name') }}" placeholder="Name" required autofocus>
                    <span class="help is-danger">{{ $errors->first('name') }}</span>
                </p>
                <p class="control">
                    <input class="input" type="email" name="email" value="{{ old('email') }}" placeholder="Email" required autofocus>
                    <span class="help is-danger">{{ $errors->first('email') }}</span>
                </p>
                <p class="control">
                    <input class="input" type="password" name="password" placeholder="Password" required>
                    <span class="help is-danger">{{ $errors->first('password') }}</span>
                </p>
                <p class="control">
                    <input class="input" type="password" name="password_confirmation" placeholder="Repeat password" required>
                    <span class="help is-danger">{{ $errors->first('password') }}</span>
                </p>
                <p class="control">
                    <button class="button blue-button" type="submit">Register</button>
                </p>

                <p>Already have an account? <a href="/login">Sign in!</a></p>
            </form>
        </div>
    </div>
</div>
@endsection

