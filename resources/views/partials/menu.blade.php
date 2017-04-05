<nav class="nav has-shadow">
	<div class="container">
		<div class="nav-left">
			<a href="/" class="nav-item">
				<img style="width:25px;" src="/images/T.svg" alt="logo">
			</a>
			@if (Auth::guest())
			<a class="nav-item is-tab is-hidden-mobile is-active">Login</a>
			@else
			<a href="/teams" class="nav-item is-tab is-hidden-mobile">My teams</a>
			<a href="/tickets" class="nav-item is-tab is-hidden-mobile">Tickets</a>
			<a href="/archive" class="nav-item is-tab is-hidden-mobile">Archive</a>
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
			<a href="/profile" class="nav-item is-tab">
				<figure class="image is-16x16" style="margin-right: 8px;">
					<img class="img-circle" src="/images/{{ Auth::user()->avatar }}">
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
