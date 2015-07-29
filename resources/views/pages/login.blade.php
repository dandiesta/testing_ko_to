@extends('pages.main')

@section('content')
<div id="signin-dialog" class="col-sm-6 col-sm-offset-3">
    <div class="panel panel-primary">
        <div class="panel-heading">
            <h2 class="panel-title">Login</h2>
        </div>

        <div class="panel-body">

            @if (Session::has('msg'))
                <div class="alert alert-danger alert-dismissible" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    {{ Session::get('msg') }}
                </div>
            @endif

            {{--@if($enable_password)--}}
            {!! Form::open(['class' => 'form-horizontal', 'route' => 'authenticate', 'method' => 'post']) !!}
                <div class="form-group">
                    {!! Form::label('email_label', 'E-mail', ['class' => 'control-label col-sm-3']) !!}

                    <div class="col-sm-9">
                        {!! Form::email('email', '', ['class' => 'form-control']) !!}
                    </div>
                </div>

                <div class="form-group">
                    {!! Form::label('password_label', 'Password', ['class' => 'control-label col-sm-3']) !!}

                    <div class="col-sm-9">
                        {!! Form::password('password', ['class' => 'form-control']) !!}
                    </div>
                </div>

                <div class="col-sm-9 col-sm-offset-3">
                    {!! Form::submit('Login', ['class' => 'btn btn-primary']) !!}
                    <a class="btn btn-link" href="#">Forget Password</a>
                </div>
            {!! Form::close() !!}

            {{--<form class="form-horizontal" method="post" action="{{ url('/login/password') }}">--}}
                {{--<div class="form-group">--}}
                    {{--<label class="control-label col-sm-3" for="email">email</label>--}}
                    {{--<div class="col-sm-9">--}}
                        {{--<input class="form-control" type="text" id="email" name="email">--}}
                    {{--</div>--}}
                {{--</div>--}}

                {{--<div class="form-group">--}}
                    {{--<label class="control-label col-sm-3" for="password">password</label>--}}
                    {{--<div class="col-sm-9">--}}
                        {{--<input class="form-control" type="password" id="password" name="password">--}}
                    {{--</div>--}}
                {{--</div>--}}

                {{--<div class="col-sm-9 col-sm-offset-3">--}}
                    {{--<input type="submit" class="btn btn-primary" value="Login">--}}
                    {{--<a class="btn btn-link" href="#">Forget Password</a>--}}
                {{--</div>--}}
            {{--</form>--}}
            {{--@endif--}}

            {{--@if($enable_google_auth)--}}
            {{--<div class="google-login col-sm-10 col-sm-offset-1">--}}
                {{--<a class="btn btn-primary col-xs-12" href="#>">Login with google account</a>--}}
            {{--</div>--}}
            {{--@endif--}}
        </div>
    </div>
</div>
@stop
