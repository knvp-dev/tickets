@extends('layouts.app')

@section('content')

<div class="container">

	<div class="floating-panel user-card has-text-centered">
	
	<h1 class="title is-uppercase has-text-centered is-text-blue">Subscription</h1>

	@if(auth()->user()->isOnGracePeriod())
		
		<p>subscription will expire on {{ $user->subscription_end_at->format('Y-m-d') }}</p>
		<form action="/subscription" method="post">
			{{ method_field('PATCH') }}
			{{ csrf_field() }}
			<button type="submit" class="button blue-button button-centered">Resume my subscription</button>
		</form>
	@endif

	@if(auth()->user()->isSubscibed())
		<p>you are currently subscribed to the <strong>{{ $user->stripe_plan }}</strong> plan for <strong>€{{ number_format(auth()->user()->subscription()->retrieveStripePlan()->amount / 100,2) }}/month</strong>.</p>
		<form action="/subscription" method="post">
			{{ method_field('DELETE') }}
			{{ csrf_field() }}

			<button type="submit" class="button blue-button button-centered">Cancel my subscription</button>
		</form>
	@endif

	@if(! auth()->user()->isSubscibed() && ! auth()->user()->isOnGracePeriod())
		<checkout-form :plans="{{ $plans }}"></checkout-form>
	@endif

	</div>

	

	@if(count(auth()->user()->payments))
	<div class="floating-panel user-card">
	<h1 class="title has-text-centered is-text-blue is-uppercase">Payments</h1>
		<ul>
			@foreach(auth()->user()->payments as $payment)
				<li>{{ $payment->created_at->format('Y-m-d') }}: <strong>€{{ number_format($payment->amount / 100, 2) }}</strong></li>
			@endforeach
		</ul>
	</div>
	@endif

	<div class="user-card floating-panel">
		<img src="/images/{{ $user->avatar }}"  alt="">
		<div class="user-card-info">
			<a href="/" class="button white-button">Change avatar</a>
			<h1>{{ $user->name }}</h1>
			<ul>
				<li>Member in {{ $user->teams->count() }} team(s)</li>
				<li>Active plan: {{ $user->stripe_plan }}</li>
			</ul>
		</div>
	</div>

</div>


@endsection