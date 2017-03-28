@extends('layouts.app')

@section('content')


<div class="container">
	<h1 class="title head-title">{{ $team->title }} members</h1>
</div>

<div class="container is-flex">

	<div class="floating-panel fill-space">
		<h1 class="title is-text-blue is-uppercase">Invite someone to your team</h1>
		<form action="/team/{{ $team->id }}/invitation/create" method="post">
		{{ csrf_field() }}
		<p class="control">
			<input type="text" class="input" name="email" placeholder="Email address">
		</p>
		<p class="control">
			<button type="submit" class="button white-button">Send invitation</a>
		</p>
		</button>
	</div>

	<div class="floating-panel fill-space">
		<h1 class="title is-text-blue is-uppercase">Team members</h1>
		<ul>
			@foreach($team->members as $teamMember)
			<li class="is-flex member-list-item">
				<img src="/images/{{ $teamMember->avatar }}" class="member-badge" alt="">
				<p class="member-name">{{ $teamMember->name }}</p>
				<a href="/" class="button white-button">Remove from team</a>
			</li>
			@endforeach
		</ul>
	</div>
</div>
</div>



@endsection