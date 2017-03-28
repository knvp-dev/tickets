@extends('layouts.fullscreen')

@section('content')
<div class="fullscreen">
    <div class="container">
        {{-- <div class="brand-icon">
            <img style="width:100px;" src="/images/T.svg" alt="">
        </div> --}}
    </div>

    <div class="container">
    @if($invitations->count() > 0)
        <div class="team-list floating-panel">
            <h1 class="title has-text-centered is-uppercase is-text-blue">Invitations</h1>
            <ul class="panel-list">
            @foreach($invitations as $invite)
                <li class="panel-list-item">
                    <p>{{ $invite->team->owner->name }} invited you to join his team:</p>
                    <p>{{ $invite->team->title }}</p>
                    <form action="/invitation/accept" method="post">
                    {{ csrf_field() }}
                        <input type="hidden" name="invitation_id" value="{{ $invite->id }}">
                        <button type="submit" class="button blue-button">Accept</button>
                    </form>
                </li>
            @endforeach
            </ul>
        </div>
        @endif
    @if($teams->count() > 0)
        <div class="team-list floating-panel">
            <h1 class="title has-text-centered is-uppercase is-text-blue">Select a team</h1>
            <ul class="panel-list">
            @foreach($teams as $team)
                <li class="panel-list-item">
                    <i class="fa fa-circle list-bullet"></i>
                    <a class="is-uppercase" href="/team/choose/{{ $team->id }}">{{ $team->title }}</a>
                </li>
            @endforeach
            </ul>
        </div>
        @endif
        <div class="login-form floating-panel has-text-centered">
            <h1 class="title has-text-centered is-uppercase is-text-blue">Create a new team</h1>
            <form class="form-horizontal" role="form" method="POST" action="{{ url('/team/create') }}">
                {{ csrf_field() }}
                <p class="control">
                    <input class="input" type="test" name="title" placeholder="Team name" required>
                    <span class="help is-danger">{{ $errors->first() }}</span>
                </p>
                <p class="control">
                    <button class="button blue-button login-submit" type="submit">Create</button>
                </p>
            </form>
        </div>
    </div>
</div>
@endsection
