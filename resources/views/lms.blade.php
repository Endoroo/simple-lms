<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="format-detection" content="telephone=no">

    <title>{{ config('app.name', 'ПромРесурс') }}</title>

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
<div id="app">
    <b-container>
        <b-navbar toggleable="md">
            <b-navbar-toggle target="nav_collapse"></b-navbar-toggle>
            <b-collapse is-nav id="nav_collapse">
                <b-navbar-nav>
                    <b-nav-item href="{{ url('/') }}">{{ __('Главная') }}</b-nav-item>
                </b-navbar-nav>

                <b-navbar-nav class="ml-auto">
                    <b-nav-item-dropdown right>
                        <!-- Using button-content slot -->
                        <template slot="button-content">
                            <em>{{ __('Пользователь') }}</em>
                        </template>
                        <b-dropdown-item href="#">{{ __('Профиль') }}</b-dropdown-item>
                        <b-dropdown-item href="#">{{ __('Выйти') }}</b-dropdown-item>
                    </b-nav-item-dropdown>
                </b-navbar-nav>
            </b-collapse>
        </b-navbar>
        @yield('content')
    </b-container>
</div>
<script type="text/javascript" src="{{ asset('js/app.js') }}"></script>
</body>
</html>