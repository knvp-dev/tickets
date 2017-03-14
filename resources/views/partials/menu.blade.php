<nav class="nav has-shadow">
	<div class="container">
		<div class="nav-left">
			<a class="nav-item">
				<img src="/images/T.png" alt="logo">
			</a>
			@if (Auth::guest())
			<a class="nav-item is-tab is-hidden-mobile is-active">Login</a>
			@else
			<router-link to="/" class="nav-item is-tab is-hidden-mobile">All tickets</router-link>
			<router-link to="/my-tickets" class="nav-item is-tab is-hidden-mobile">Assigned to me</router-link>
			<router-link to="/archive" class="nav-item is-tab is-hidden-mobile">Archive</router-link>
			@endif
		</div>
		<span class="nav-toggle">
			<span></span>
			<span></span>
			<span></span>
		</span>
		<div class="nav-right nav-menu">
			@if (Auth::guest())
			<a class="nav-item is-tab is-hidden-mobile is-active">Login</a>
			@else
			<a href="/tickets" class="nav-item is-tab is-hidden-tablet">Tickets</a>
			<a class="nav-item is-tab">
				<figure class="image is-16x16" style="margin-right: 8px;">
					<img class="is-rounded" src="{{ Auth::user()->avatar }}">
				</figure>
				{{ Auth::user()->name }}
			</a>
			<a class="nav-item is-tab" href="{{ url('/logout') }}"
			onclick="event.preventDefault();
			document.getElementById('logout-form').submit();">
			Logout
		</a>
		@endif
		<form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
			{{ csrf_field() }}
		</form>
	</div>
</div>
</nav>