@extends('layouts.app')

@section('content')

<div class="container is-flex">

	<div class="is-stacked">

		<div class="user-card floating-panel">
			<img src="/images/{{ $user->avatar }}"  alt="">
			<div class="user-card-info">
				<a href="/" class="button white-button">Change avatar</a>
				<h1>{{ $user->name }}</h1>
				<ul>
					<li>Member in {{ $user->teams_count }} team(s)</li>
					<li>Active plan: {{ $user->stripe_plan ?? 'free' }}</li>
				</ul>
			</div>
		</div>

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

			@if(auth()->user()->isSubscribed())
			<p>you are currently subscribed to the <strong>{{ $user->stripe_plan }}</strong> plan for <strong>€{{ number_format(auth()->user()->subscription()->retrieveStripePlan()->amount / 100,2) }}/month</strong>.</p>
			<form action="/subscription" method="post">
				{{ method_field('DELETE') }}
				{{ csrf_field() }}

				<button type="submit" class="button blue-button button-centered">Cancel my subscription</button>
			</form>
			@endif

			@if(! auth()->user()->isSubscribed() && ! auth()->user()->isOnGracePeriod())
			<p>You are currently not on a subscription</p>
			<checkout-form :plans="{{ $plans }}"></checkout-form>
			@endif

		</div>

	</div>
	<div class="is-stacked fill-space">
		<div class="floating-panel">
			<h1 class="title has-text-centered is-text-blue is-uppercase">Teams</h1>
			<ul>
				@foreach($teams as $team)
				<li class="table-row is-flex is-aligned">
					<p><i class="fa fa-users is-medium-icon mr-10"></i>{{ $team->title }}</p>
					@if(session('team_id') != $team->id)
					<p class="flex-aligned-right"><a href="/team/choose/{{ $team->id }}" class="button white-button">Switch to team</a></p>
					@else
					<p class="flex-aligned-right">Active team</p>
					@endif
				</li>
				@endforeach
			</ul>
			{{ $teams->links() }}
		</div>
		@if(count(auth()->user()->payments))
		<div class="floating-panel">
			<h1 class="title has-text-centered is-text-blue is-uppercase">Payments</h1>
			<ul>
				@foreach($payments as $payment)
				<li class="table-row is-flex is-aligned">
					<p><i class="fa fa-credit-card is-medium-icon mr-10"></i>A payment was made on {{ $payment->created_at->format('d-m-Y') }} </p>
					<p class="flex-aligned-right"><strong>€{{ number_format($payment->amount / 100, 2) }}</strong></p>
				</li>
				@endforeach
			</ul>
			{{ $payments->links() }}
		</div>
		@endif

	</div>
</div>


@endsection