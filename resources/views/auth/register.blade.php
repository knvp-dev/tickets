@extends('layouts.fullscreen')

@section('content')
<div class="fullscreen">
    <div class="container">
        <div class="brand-icon">
            <img style="width:100px;" src="/images/T.svg" alt="">
        </div>
    </div>

    <div class="container">
        <div class="login-form">
            <h1 class="title has-text-centered">Register</h1>
            <form class="form-horizontal" role="form" method="POST" action="{{ url('/register') }}">
                {{ csrf_field() }}
                <p class="control">
                    <input class="input is-underline-input is-large" type="text" name="name" value="{{ old('name') }}" placeholder="Name" required autofocus>
                    <span class="help is-danger">{{ $errors->first('name') }}</span>
                </p>
                <p class="control">
                    <input class="input is-underline-input is-large" type="email" name="email" value="{{ old('email') }}" placeholder="Email" required autofocus>
                    <span class="help is-danger">{{ $errors->first('email') }}</span>
                </p>
                <p class="control">
                    <input class="input is-underline-input is-large" type="password" name="password" placeholder="Password" required>
                    <span class="help is-danger">{{ $errors->first('password') }}</span>
                </p>
                <p class="control">
                    <input class="input is-underline-input is-large" type="password" name="password_confirmation" placeholder="Repeat password" required>
                    <span class="help is-danger">{{ $errors->first('password') }}</span>
                </p>
                <p class="control">
                    <button class="add-button login-submit" type="submit"><i class="fa fa-arrow-right"></i></button>
                </p>
            </form>
        </div>
    </div>
</div>
@endsection

