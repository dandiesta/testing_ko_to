<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    {{--<title>{{ (isset($page_title))?htmlspecialchars($page_title).' | ':'' }} {{ $title_prefix }} EMLauncher</title>--}}
    <title>EMLauncher</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    {{--@if(mfwServerEnv::getEnv()==='local')--}}
    {{--<link href="/bootstrap/bootswatch/spacelab/bootstrap.min.css" rel="stylesheet" media="screen">--}}
    {{--<script src="/jquery/jquery.js"></script>--}}
    {{--<script src="/bootstrap/3.0.0/js/bootstrap.min.js"></script>--}}
    {{--<link href="/font-awesome/4.0.1/css/font-awesome.min.css" rel="stylesheet">--}}
    {{--@else--}}
    <link href="//netdna.bootstrapcdn.com/bootswatch/3.0.0/spacelab/bootstrap.min.css" rel="stylesheet" media="screen">
    <script src="//code.jquery.com/jquery.js"></script>
    <script src="//netdna.bootstrapcdn.com/bootstrap/3.0.0/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
    {{--@endif--}}
    <link rel="stylesheet" href="{{ asset('css/customize.css') }}" type="text/css">
    <link rel="apple-touch-icon" href="{{ asset('apple-touch-icon.png') }}">
    <link rel="shortcut icon" href="{{ asset('/favicon.ico') }}">
</head>
<body>

<div class="navbar navbar-inverse" role="navigation">
    <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>
        <a href="{{ route('top_apps') }}" class="navbar-brand"><span>EMLauncher</span></a>
    </div>
    <div class="collapse navbar-collapse navbar-ex1-collapse">
        @if(Auth::check())
        <ul class="nav navbar-nav">
            <li><a href="{{ route('top_apps') }}">Top</a></li>
            <li class="dropdown">
                <a hfer="#" class="dropdown-toggle" data-toggle="dropdown">My apps <b class="caret"></b></a>
                <ul class="dropdown-menu">
                    <li><a href="{{ route('installed_apps') }}">Installed Apps</a></li>
                    <li><a href="{{ route('my_apps') }}">Own Apps</a></li>
                </ul>
            </li>
            <li><a href="{{ route('docs') }}">API doc</a></li>
        </ul>

        <ul class="nav navbar-nav navbar-right">
            <li class="dropdown">
                {{--<a href="#" class="dropdown-toggle" data-toggle="dropdown">--}}{{-- htmlspecialchars($login_user->getMail()) --}}{{-- <b class="caret"></b></a>--}}
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">{{ Auth::user()->mail }} <b class="caret"></b></a>
                <ul class="dropdown-menu">
                    <li><a href="{{ route('logout') }}">Logout</a></li>
                </ul>
            </li>
        </ul>
        @else
        <ul class="nav navbar-nav navbar-right">
            <li><a href="{{ route('login') }}">Login</a></li>
        </ul>
        @endif
    </div>
</div>

<div class="container">
    @yield('content')
</div>

@yield('scripts')
</body>
</html>