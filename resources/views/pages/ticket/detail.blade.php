@extends('layouts.app')

@section('content')

{{-- <ticket-detail data="{{ json_encode($ticket) }}"></ticket-detail> --}}


<div class="container">
	<h1 class="title">My first ticket</h1>
</div>

<div class="container is-flex">
	<div class="floating-panel has-text-centered fill-space">
{{-- 	<h1 class="title is-uppercase is-text-blue">My first ticket</h1> --}}
	<div class="ticket-details">
	<div class="ticket-details-block">
			<h1>Created at</h1>
			<p>12-05-2014</p>
		</div>
		<div class="ticket-details-block">
			<h1>Category</h1>
			<p>Webdevelopment</p>
		</div>
		<div class="ticket-details-block">
			<h1>Priority</h1>
			<p>Normal</p>
		</div>
		<div class="ticket-details-block">
			<h1>Members</h1>
			<p>3 / 5</p>
		</div>
		<div class="ticket-details-block">
			<h1>Status</h1>
			<p>Ticket is ongoing</p>
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
			<li class="team-member">
				<img class="member-badge" src="/images/default.jpg" alt="">
			</li>
			<li class="team-member">
				<img class="member-badge" src="/images/default.jpg" alt="">
			</li>
		</ul>
		<button class="button white-button">Manage ticket members</button>
	</div>
</div>

<div class="container is-flex">
	<div class="floating-panel has-text-centered fill-space">
		<h1 class="title is-uppercase is-text-blue">Todos </h1>
		<div class="todo-form">
			<input type="text" class="input" name="task" placeholder="New task">
			<button class="button white-button">Add task</button>
		</div>
		<hr>
		<ul class="todo-list">
			<li class="todo-item is-flex">
				<i class="fa fa-circle-o is-small-icon ticket-status-icon status-idle"></i>
				<div class="todo-content">
					<p>Lorem ipsum dolor sit amet, consectetur adipisicing</p>
					<span class="has-lighter-text">Completed 4 weeks ago</span>
				</div>
				<div class="todo-controls">
					<a href="/">
					<i class="fa fa-check rounded-icon-button is-small-icon"></i>
					</a>
					<a href="/">
						<i class="fa fa-remove rounded-icon-button is-small-icon"></i>
					</a>
				</div>
			</li>
			<li class="todo-item is-flex">
				<i class="fa fa-check is-small-icon ticket-status-icon status-active"></i>
				<div class="todo-content">
					<p>Lorem ipsum dolor sit amet, consectetur adipisicing</p>
					<span class="has-lighter-text">Completed 4 weeks ago</span>
				</div>
				<div class="todo-controls">
					<a href="/">
					<i class="fa fa-refresh rounded-icon-button is-small-icon"></i>
					</a>
					<a href="/">
						<i class="fa fa-remove rounded-icon-button is-small-icon"></i>
					</a>
				</div>
			</li>
		</ul>
	</div>
	<div class="floating-panel has-text-centered fill-space">
		<h1 class="title is-uppercase is-text-blue">Messages</h1>
		<div class="message-form">
			<input class="input" type="text" name="message" placeholder="Message">
			<button class="button white-button">send</button>
		</div>
		<hr>
		<ul class="message-list">
			<li class="message-item">
				<img src="/images/default.jpg" class="member-badge" />
				<div class="message-content">
					<div class="message-box">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod.
						<span class="message-date">2 days ago</span>
					</div>
				</div>
			</li>
		</ul>
	</div>
</div>

@endsection