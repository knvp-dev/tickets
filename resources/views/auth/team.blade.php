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
            <h1 class="title has-text-centered">Create a new team</h1>
            <form class="form-horizontal" role="form" method="POST" action="{{ url('/team/create') }}">
                {{ csrf_field() }}
                <p class="control">
                    <input class="input is-underline-input is-large" type="test" name="title" placeholder="Team name" required>
                    <span class="help is-danger">{{ $errors->first() }}</span>
                </p>
                <p class="control">
                    <button class="add-button login-submit" type="submit"><i class="fa fa-arrow-right"></i></button>
                </p>
            </form>
        </div>
        <div class="team-list">
            <h1 class="title has-text-centered">Select a team</h1>
            @foreach($teams as $team)
            <div class="team-list-item">
                <h2 class="subtitle"><a href="/team/choose/{{ $team->id }}">{{ $team->title }}</a></h2>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
