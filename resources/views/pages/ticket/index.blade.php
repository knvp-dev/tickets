@extends('layouts.app')

@section('content')

<div class="container">
	<div class="floating-panel action-panel">
		<div>
			<h1>Team members</h1>
		</div>
		<ul class="team-members">
			@foreach($team->members as $member)
			<li class="team-member">
				<img class="member-badge" src="/images/{{ $member->avatar }}" alt="">
			</li>
			@endforeach
		</ul>
		@if(auth()->id() == $team->owner->id)
			<a href="/team/{{ $team->id }}/members" class="button white-button">Manage team members</a>
		@endif
	</div>
</div>

<div class="container is-flex">
	<div class="floating-panel has-text-centered">
		<h1>{{ $team->title }}</h1>
		<hr>
		<h1 class="title is-uppercase is-text-blue">Create new ticket</h1>
		<form action="/ticket/save" method="post">
			{{ csrf_field() }}
			<p class="control">
				<input class="input" type="text" name="title" value="{{ old('name') }}" placeholder="Title" required autofocus>
				<span class="help is-danger">{{ $errors->first('title') }}</span>
			</p>
			<div class="control">
				<div class="select is-fullwidth">
					<select name="category_id">
						<option value="" disabled selected>Category</option>
						@foreach($team->categories as $category)
						<option value="{{ $category->id }}">{{ $category->name }}</option>
						@endforeach
					</select>
				</div>
			</div>
			<div class="control">
				<div class="select is-fullwidth">
					<select name="priority_id">
						<option value="" disabled selected>Priority</option>
						@foreach(App\Priority::all() as $priority)
							<option value="{{ $priority->id }}">{{ $priority->name }}</option>
						@endforeach
					</select>
				</div>
			</div>

			<p class="control">
				<button class="button blue-button" type="submit">Create ticket</button>
			</p>
		</form>

		<hr>

		<h1 class="title is-uppercase is-text-blue">Categories</h1>

		<ul class="panel-list">
			<li class="panel-list-item">
				<i class="fa fa-circle list-bullet"></i>
				<a href="/tickets">All tickets</a>
			</li>
			@foreach($categories as $category)
				<li class="panel-list-item">
					<i class="fa fa-circle list-bullet"></i>
					<a href="/tickets/{{ $category->slug }}">{{ $category->name }}</a>
				</li>
			@endforeach
		</ul>
	</div>

	<div class="floating-panel fill-space">
		<h1 class="title has-text-centered is-uppercase is-text-blue">Tickets</h1>
		<div class="tickets">
			<div class="tickets-list">
				<ul>
				@if(count($tickets))
				@foreach($tickets as $ticket)
					<li class="ticket-item">
						<div class="ticket-info">
							<i class="fa fa-check is-small-icon ticket-status-icon status-active"></i>
							<div class="ticket-item-info">
								<h1 class="ticket-title">{{ $ticket->title }}</h1>
								<span>
									<p>created {{ $ticket->created_at->diffForHumans() }} by {{ $ticket->owner->name }}</p>
								</span>
							</div>
						</div>
						<ul class="ticket-members">
							@foreach($ticket->members as $member)
								<li class="ticket-member">
									<img class="member-badge" src="/images/{{ $member->avatar }}" alt="">
								</li>
							@endforeach
						</ul>
						<span class="member-control">
							<a href="{{ $ticket->path() }}">
								<i class="fa fa-arrow-right rounded-icon-button is-small-icon"></i>
							</a>
						</span>
					</li>
					@endforeach
					@else
					<p class="has-text-centered">There are no tickets</p>
					@endif
				</ul>
			</div>
		</div>
	</div>
</div>

@endsection