<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Konvert') }}</title>

    <!-- Styles -->
    <link href="/css/app.css" rel="stylesheet">

    <!-- Scripts -->
    <script>
        window.Laravel = {!! json_encode([
            'csrfToken' => csrf_token(),
            ]) !!};
        </script>
    </head>
    <body>
        <div id="app">

          

            <nav class="nav has-shadow">
              <div class="container">
                <div class="nav-left">
                  <a class="nav-item">
                    <img src="images/logo.svg" alt="logo">
                </a>
                @if (Auth::guest())
                <a class="nav-item is-tab is-hidden-mobile is-active">Login</a>
                @else
                <a href="/tickets" class="nav-item is-tab is-hidden-mobile">Tickets</a>
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

@yield('content')
</div>

<!-- Scripts -->
<script src="/js/app.js"></script>
</body>
</html>
