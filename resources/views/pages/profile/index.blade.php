@extends('layouts.app')

@section('content')

<div class="container">
	
	Upgrade plan

	<checkout-form :plans="{{ $plans }}"></checkout-form> 

	<div class="user-card floating-panel">
		<img src="/images/{{ $user->avatar }}"  alt="">
		<div class="user-card-info">
			<a href="/" class="button white-button">Change avatar</a>
			<h1>{{ $user->name }}</h1>
			<ul>
				<li>Member in {{ $user->teams->count() }} team(s)</li>
				<li>Active plan: </li>
			</ul>
		</div>
	</div>

</div>


@endsection