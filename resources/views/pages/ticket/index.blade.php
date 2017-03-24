@extends('layouts.app')

@section('content')

<div class="container">
	<div class="floating-panel action-panel">
		<div>
			<h1>Team members</h1>
		</div>
		<ul class="team-members">
			<li class="team-member">
				<img class="member-badge" src="/images/default.jpg" alt="">
			</li>
			<li class="team-member">
				<img class="member-badge" src="/images/default.jpg" alt="">
			</li>
		</ul>
		<button class="button white-button">Manage team members</button>
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
					<select name="category">
						<option value="" disabled selected>Category</option>
						@foreach($team->categories as $category)
						<option value="{{ $category->id }}">{{ $category->name }}</option>
						@endforeach
					</select>
				</div>
			</div>
			<div class="control">
				<div class="select is-fullwidth">
					<select name="priority">
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
				<a href="/">Webdevelopment</a>
			</li>
			<li class="panel-list-item">
				<i class="fa fa-circle list-bullet"></i>
				<a href="/">Copywriting</a>
			</li>
			<li class="panel-list-item">
				<i class="fa fa-circle list-bullet"></i>
				<a href="/">Webdevelopment</a>
			</li>
			<li class="panel-list-item">
				<i class="fa fa-circle list-bullet"></i>
				<a href="/">Copywriting</a>
			</li>
			<li class="panel-list-item">
				<i class="fa fa-circle list-bullet"></i>
				<a href="/">Webdevelopment</a>
			</li>
			<li class="panel-list-item">
				<i class="fa fa-circle list-bullet"></i>
				<a href="/">Copywriting</a>
			</li>
		</ul>
	</div>

	<div class="floating-panel fill-space">
		<h1 class="title has-text-centered is-uppercase is-text-blue">Tickets</h1>
		<div class="tickets">
			<div class="tickets-list">
				<ul>
				@foreach($team->tickets as $ticket)
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
									<img class="member-badge" src="/images/default.jpg" alt="">
								</li>
							@endforeach
						</ul>
						<span class="member-control">
							<a href="/">
								<i class="fa fa-plus rounded-icon-button is-small-icon"></i>
							</a>
							<a href="/ticket/1">
								<i class="fa fa-arrow-right rounded-icon-button is-small-icon"></i>
							</a>
						</span>
					</li>
					@endforeach
				</ul>
			</div>
		</div>
	</div>
</div>

@endsection