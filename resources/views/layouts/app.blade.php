<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Milestone</title>

    <!-- Scripts -->
    <script src="{{ mix('js/app.js', 'vendor/milestone') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ mix('css/app.css', 'vendor/milestone') }}" rel="stylesheet">
</head>
<style>
    
body {
    height: 100vh;
}

#app {
    height: 100%;
}

main {
    height: 100%;
}

.goal-set-table input[type="number"] {
    width: 80px;
    padding-left: 10px;
    text-align: right;
    margin-right: 10px;
}

.goal-period-stat {
    font-size: 1.5rem;
    padding: 0.5rem;
    text-align: center;
    line-height: 0.8;
}

.goal-period-points-text {
    font-size: 1rem;
}

.panel-board {
    height: 100%;
}

.documents-panel {
    padding: 15px;
    margin: 10px;
    border: 1px solid #e0e0e0;
}

.documents-left-panel {
    width: 400px;
    margin-left: 30px;
}

.documents-center-panel {
    
}

.documents-right-panel {
    width: 400px;
    margin-right: 30px;
}

.document-list-view {
    overflow-y: auto;
}

.documents-list {
    list-style: none;
    padding: 0;
    overflow-y: auto;
}

.documents-list-item .card {
    border: none;
}

.panel-tabs {
    list-style: none;
    padding: 0;
    display: flex;
    background: #e0e0e0;
    margin: -15px -15px 15px;
}

.panel-tab {
    border-right: 1px solid #d0d0d0;
    padding: 5px 15px;
    background: #f0f0f0;
}

.panel-tab.active {
    background: #fff;
}

.annotations-list {
    list-style: none;
    padding: 0;
    overflow-y: auto;
}

.annotations-list-item .card {
    border: none;
}
</style>
<body>
    <div id="app" class="d-flex flex-column">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    Milestone
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('goalSets') }}">Goal Sets</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('goals.periods') }}">Goal Periods</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('goals.import') }}">Import</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('documents.index') }}">Documents</a>
                        </li>
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Authentication Links -->
                        @guest
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                            </li>
                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }} <span class="caret"></span>
                                </a>

                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">

                                    <a class="dropdown-item" href="{{ route('settings.index') }}">
                                       Settings
                                    </a>
                                    
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            <div class="container">
                @if (session('success_message'))
                <div class="alert alert-success">{{ session('success_message') }}</div>
                @endif
                @if (session('error_message'))
                <div class="alert alert-danger">{{ session('error_message') }}</div>
                @endif
            </div>
            @yield('content')
        </main>
    </div>
</body>
</html>
