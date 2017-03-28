@extends('layouts.app')

@section('content')


<div class="container">
	<h1 class="title head-title">{{ $ticket->title }} members</h1>
</div>

<div class="container is-flex">

	<div class="floating-panel fill-space">
		<h1 class="title is-text-blue is-uppercase">Ticket members</h1>
		<ul>
			@foreach($ticket->members as $ticketMember)
				<li class="is-flex member-list-item">
					<img src="/images/{{ $ticketMember->avatar }}" class="member-badge" alt="">
					<p class="member-name">{{ $ticketMember->name }}</p>
					@if($ticketMember->id != auth()->id())
						<a href="/ticket/{{ $ticket->slug }}/unassign/{{ $ticketMember->id }}" class="button white-button">Remove from ticket</a>
					@endif
				</li>
			@endforeach
		</ul>
	</div>

	<div class="floating-panel fill-space">
		<h1 class="title is-text-blue is-uppercase">Team members</h1>
		<ul>
			@foreach($teamMembers as $teamMember)
				<li class="is-flex member-list-item">
					<img src="/images/{{ $teamMember->avatar }}" class="member-badge" alt="">
					<p class="member-name">{{ $teamMember->name }}</p>
					<a href="/ticket/{{ $ticket->slug }}/assign/{{ $teamMember->id }}" class="button white-button">Add to ticket</a>
				</li>
			@endforeach
		</ul>
	</div>
</div>
</div>



@endsection