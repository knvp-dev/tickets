@extends('layouts.app')

@section('content')

<div class="container is-flex is-head">
	<h1 class="title">{{ $ticket->title }}</h1>
	@if($ticket->owner->id == auth()->id())
	<div>
		@if(!$ticket->completed)
		<a href="/ticket/{{ $ticket->slug }}/complete" class="button white-button">Close ticket</a>
		@else
		<a href="/ticket/{{ $ticket->slug }}/uncomplete" class="button white-button">Reopen ticket</a>
		@endif
		<a href="/ticket/{{ $ticket->slug }}/delete" class="button white-button">Delete ticket</a>
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
				<p>{{ $ticket->members->count() }} / {{ $ticket->team->size }}</p>
			</div>
			<div class="ticket-details-block">
				<h1>Status</h1>
				<p>Ticket is {{ $ticket->status->name }}</p>
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
				<img class="member-badge" src="/images/{{ $member->avatar }}" alt="">
			</li>
			@endforeach
		</ul>

		@if(auth()->user()->ownsTicket($ticket))
		<a href="{{ $ticket->path() }}/members" class="button white-button">Manage ticket members</a>
		@endif
	</div>
</div>

<div class="container is-flex">
	<div class="floating-panel has-text-centered fill-space">
		<h1 class="title is-uppercase is-text-blue">Todos</h1>
		@if(auth()->user()->isAssignedToTicket($ticket))
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
					<span class="has-lighter-text">Added {{ $todo->created_at->diffForHumans() }}</span>
				</div>
				@if(auth()->user()->isAssignedToTicket($ticket))
				<div class="todo-controls">
					<a href="/ticket/{{ $ticket->slug }}/todo/{{ $todo->id }}/complete">
						<i class="fa fa-check rounded-icon-button is-small-icon"></i>
					</a>
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
		@if(auth()->user()->isAssignedToTicket($ticket))
		<form class="message-form" method="post" action="/ticket/{{ $ticket->slug }}/messages/create">
			{{ csrf_field() }}
			<input class="input" type="text" name="body" placeholder="Message">
			<button class="button white-button">send</button>
		</form>
		<hr>
		@endif
		<ul class="message-list">
			@foreach($ticket->messages as $message)
			<li class="message-item">
				<img src="/images/{{ $message->user->avatar }}" class="member-badge" />
				<div class="message-content">
					<div class="message-box">{{ $message->body }}
						<span class="message-date">{{ $message->created_at->diffForHumans() }}</span>
					</div>
				</div>
			</li>
			@endforeach
		</ul>
	</div>
</div>

@endsection