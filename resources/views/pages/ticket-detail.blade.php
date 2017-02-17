@extends('layouts.app')

@section('content')

<div class="title-bar">
	<div class="container">
		<h1>Ticket details</h1>
	</div>
</div>

<ticket-detail ticketid="{{ $ticket_id }}"></ticket-detail>

@endsection