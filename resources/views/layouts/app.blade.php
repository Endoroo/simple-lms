@php($locale = app()->getLocale())
@php($title = __(config('app.name', 'Trader')))
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', $locale) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name') }}</title>

    <!-- Styles -->
    <link href="{{ asset('css/app.css', Request::secure()) }}" rel="stylesheet">
</head>
<body>
    <div id="web-gui">
        @guest
            <nav class="navbar navbar-expand-md navbar-light navbar-laravel">
                <div class="container">
                    <a class="navbar-brand" href="{{ url('/') }}">
                        {{ $title }}
                    </a>
                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <!-- Right Side Of Navbar -->
                        <ul class="navbar-nav ml-auto">
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                            </li>
                            <li class="nav-item">
                                @if (Route::has('register'))
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                @endif
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>

            <main class="py-4">
                @yield('content')
            </main>
        @else
            <b-container class="d-none" ref="webGui" fluid>
                <b-navbar toggleable="md" class="navbar-light navbar-laravel container">
                    <b-navbar-toggle target="nav_collapse"></b-navbar-toggle>
                    <b-navbar-brand href="{{ url('/') }}" ref="interfaceTitle">{{ config('app.name') }}</b-navbar-brand>
                    <b-collapse is-nav id="nav_collapse">
                        <b-navbar-nav class="ml-auto">
                            <b-nav-item-dropdown text="{{ Auth::user()->name }}" right>
                                <b-dropdown-item href="{{ url('/') }}">{{ __('Main page') }}</b-dropdown-item>
                                <b-dropdown-item href="#" @click="logout">{{ __('Logout') }}
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                                </b-dropdown-item>
                            </b-nav-item-dropdown>
                        </b-navbar-nav>
                    </b-collapse>
                </b-navbar>

                <main>
                    @yield('content')
                </main>
            </b-container>
        @endguest
    </div>
    <script src="{{ asset('js/app.js', Request::secure()) }}"></script>
</body>
</html>
