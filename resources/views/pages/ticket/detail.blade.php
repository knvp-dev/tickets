@extends('layouts.app')

@section('content')

	{{-- <ticket-detail data="{{ json_encode($ticket) }}"></ticket-detail> --}}

	<div class="container is-flex">
		<div class="floating-panel has-text-centered fill-space">
			<h1 class="title is-uppercase is-text-blue">Todos</h1>
		</div>
		<div class="floating-panel has-text-centered fill-space">
			<h1 class="title is-uppercase is-text-blue">Messages</h1>
		</div>
	</div>

@endsection