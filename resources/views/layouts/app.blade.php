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
      'stripeKey' => config('services.stripe.key'),
      'user' => auth()->user(),
      ]) !!};
    </script>
  </head>
  <body>
    <div id="app">
      @include('partials.menu')
      @if(session()->has('message'))
      <div class="notification has-text-centered is-success">
        {{ session()->get('message') }}
      </div>
      @endif
      @yield('content')
    </div>

    <!-- Scripts -->
    <script src="https://checkout.stripe.com/checkout.js"></script>
    <script src="/js/app.js"></script>
  </body>
  </html>
