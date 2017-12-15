@extends('layouts.app')

@section('content')


<div class="container is-head">
	<h1 class="title">{{ $team->title }}: members</h1>
</div>

<div class="container is-flex">
	<div class="floating-panel fill-space">
		<h1 class="title is-text-blue is-uppercase">Team members</h1>
		<ul>
			@foreach($team->members as $teamMember)
			<li class="is-flex member-list-item">
				<img src="{{ $teamMember->getAvatarUrl() }}" class="member-badge" alt="">
				<p class="member-name">{{ $teamMember->name }}</p>
				@if($teamMember->id != $team->owner->id)
				<a href="{{ $team->path() }}/members/{{ $teamMember->id }}/remove" class="button white-button">Remove from team</a>
				@endif
			</li>
			@endforeach
		</ul>
	</div>


	<div class="container">

		<div class="floating-panel fill-space">
			<h1 class="title is-text-blue is-uppercase">Invite someone to your team</h1>
			<form action="{{ $team->path() }}/invitation/create" method="post">
				{{ csrf_field() }}
				<p class="control">
					<input type="text" class="input" name="email" placeholder="Email address">
					<span class="help is-danger">{{ $errors->first() }}</span>
				</p>
				<p class="control">
					<button type="submit" class="button white-button">Send invitation</a>
					</p>
				</button>
			</div>

			<div class="floating-panel fill-space">
				<h1 class="title is-text-blue is-uppercase">Open invitations</h1>
				@if(count($team->invitations))
				<ul>
					@foreach($team->invitations as $invitation)
					<li class="is-flex member-list-item">
						<p class="member-name">{{ $invitation->email }}</p>
						<a href="{{ $team->path() }}/invitation/{{ $invitation->id }}/cancel" class="button white-button">Cancel invitation</a>
					</li>
					@endforeach
				</ul>
				@else
				<p class="has-text-centered">There are no open invitations</p>
				@endif
			</div>	
		</div>
	</div>
	@endsection