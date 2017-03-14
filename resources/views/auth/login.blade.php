@extends('layouts.fullscreen')

@section('content')
<div class="fullscreen">
    <div class="container">
        <div class="brand-icon">
            <img src="/images/T.png" alt="">
        </div>
    </div>

    <div class="container">
        <div class="login-form">
            {{-- <h1 class="title has-text-centered">Login</h1> --}}
            <form class="form-horizontal" role="form" method="POST" action="{{ url('/login') }}">
                {{ csrf_field() }}
                <p class="control">
                    <input class="input is-underline-input is-large" type="email" name="email" value="{{ old('email') }}" placeholder="Email" required autofocus>
                    <span class="help is-danger">{{ $errors->first('email') }}</span>
                </p>
                <p class="control">
                    <input class="input is-underline-input is-large" type="password" name="password" placeholder="Wachtwoord" required>
                    <span class="help is-danger">{{ $errors->first('password') }}</span>
                </p>
                <p class="control">
                    <button class="add-button login-submit" type="submit"><i class="fa fa-unlock"></i></button>
                </p>
            </form>
        </div>
    </div>
</div>
@endsection
