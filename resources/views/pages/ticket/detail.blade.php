@extends('layouts.app')

@section('content')

<div class="container is-flex is-head">
	<h1 class="title">{{ $ticket->title }}</h1>
	@if($ticket->owner->id == $user->id)
	<div class="is-flex is-stacked">
	<div class="is-flex is-aligned">
		@if(!$ticket->completed)
		<a href="/ticket/{{ $ticket->slug }}/complete" class="button white-button">Close ticket</a>
		@else
		<a href="/ticket/{{ $ticket->slug }}/uncomplete" class="button white-button">Reopen ticket</a>
		@endif
		<form action="/ticket/{{ $ticket->slug }}/delete" method="post">
			{{ csrf_field() }}
			{{ method_field('DELETE') }}
			<button type="submit" class="button white-button">Delete ticket</button>
		</form>
		</div>
		<a href="/ticket/{{ $ticket->slug }}/edit" class="button blue-button">Edit ticket details</a>
	</div>
	@endif
</div>

<div class="container is-flex">
	<div class="floating-panel has-text-centered fill-space">
		<div class="ticket-details">
			<div class="ticket-details-block">
				<h1>Created at</h1>
				<p>{{ $ticket->created_at->diffForHumans() }}</p>
			</div>
			<div class="ticket-details-block">
				<h1>Category</h1>
				<p>{{ $ticket->category->name }}</p>
			</div>
			<div class="ticket-details-block">
				<h1>Priority</h1>
				<p>{{ $ticket->priority->name }}</p>
			</div>
			<div class="ticket-details-block">
				<h1>Members</h1>
				<p>{{ $ticket->members_count }} / {{ $ticket->team->size }}</p>
			</div>
			<div class="ticket-details-block">
				<h1>Status</h1>
				<p>Ticket is {{ $ticket->status->name }}</p>
			</div>
			<div class="ticket-details-block">
				<h1>Progress</h1>
				<p>{{ $ticket->progressInPercent() }}%</p>
			</div>
		</div>
	</div>
</div>

<div class="container">
	<div class="floating-panel action-panel">
		<div>
			<h1>Ticket members</h1>
		</div>
		<ul class="team-members">
			@foreach($ticket->members as $member)
			<li class="team-member">
				<span class="tooltip">{{ $member->name }}</span>
				<img class="member-badge" src="{{ $member->getAvatarUrl() }}" alt="">
			</li>
			@endforeach
		</ul>

		@if($user->ownsTicket($ticket))
		<a href="{{ $ticket->path() }}/members" class="button white-button">Manage ticket members</a>
		@endif
	</div>
</div>

<div class="container is-flex">
	<div class="floating-panel action-panel is-stacked fill-space">
		<h1 class="title is-uppercase is-text-blue">Description</h1>
		<p>{{ $ticket->description }}</p>

	</div>
</div>

<div class="container is-flex">
	<div class="floating-panel has-text-centered fill-space">
	<progress class="is-primary progress progress-sticky-top is-small" value="{{ $ticket->progressInPercent() }}" max="100">{{ $ticket->progressInPercent() }}</progress>
		<h1 class="title is-uppercase is-text-blue">Todos</h1>
		@if($user->isAssignedToTicket($ticket))
		<form class="todo-form" method="post" action="/ticket/{{ $ticket->slug }}/todo/save">
			{{ csrf_field() }}
			<input type="text" class="input" name="body" placeholder="New task">
			<button type="submit" class="button white-button">Add task</button>
		</form>
		<hr>
		@endif
		<ul class="todo-list">
			@foreach($ticket->todos as $todo)
			<li class="todo-item is-flex">
				@if(! $todo->completed)
				<i class="fa fa-circle-o is-small-icon ticket-status-icon status-idle"></i>
				@else
				<i class="fa fa-check is-small-icon ticket-status-icon status-active"></i>
				@endif
				<div class="todo-content">
					<p>{{ $todo->body }}</p>
					@if(! $todo->completed)
					<span class="has-lighter-text">Added {{ $todo->created_at->diffForHumans() }}</span>
					@else
					<span class="has-lighter-text">Completed by {{ $todo->resolver->name }}</span>
					@endif
				</div>
				@if($user->isAssignedToTicket($ticket))
				<div class="todo-controls">
				@if(! $todo->completed)
					<a href="/ticket/{{ $ticket->slug }}/todo/{{ $todo->id }}/complete">
						<i class="fa fa-check rounded-icon-button is-small-icon"></i>
					</a>
					@else
					<a href="/ticket/{{ $ticket->slug }}/todo/{{ $todo->id }}/uncomplete">
						<i class="fa fa-refresh rounded-icon-button is-small-icon"></i>
					</a>
					@endif
					<a href="/ticket/{{ $ticket->slug}}/todo/{{ $todo->id}}/delete">
						<i class="fa fa-remove rounded-icon-button is-small-icon"></i>
					</a>
				</div>
				@endif
			</li>
			@endforeach
		</ul>
	</div>
	<div class="floating-panel has-text-centered fill-space">
		<h1 class="title is-uppercase is-text-blue">Messages</h1>
		@if($user->isAssignedToTicket($ticket))
		<form class="message-form" method="post" action="/ticket/{{ $ticket->slug }}/messages/create">
			{{ csrf_field() }}
			<input class="input" type="text" name="body" placeholder="Message">
			<button class="button white-button">send</button>
		</form>
		<hr>
		@endif
		<ul class="message-list mh-250">
			@foreach($ticket->messages as $message)
			<li class="message-item">
				{{-- <span class="tooltip">{{ $member->name }}</span> --}}
				<img src="{{ $message->user->getAvatarUrl() }}" class="member-badge" />
				<div class="message-content">
					<div class="message-box">{{ $message->body }}
						<span class="message-date">{{ $message->user->name }}, {{ $message->created_at->diffForHumans() }}</span>
					</div>
				</div>
			</li>
			@endforeach
		</ul>
	</div>
</div>

@endsection