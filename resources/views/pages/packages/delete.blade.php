@extends('pages.main')

@section('content')

    <div class="media">
        <p class="pull-left">
            <a href="{{ route('app', ['id' => $app->app_id]) }}">
                <img class="app-icon media-object img-rounded" src="{{ env('AWS_S3_ENDPOINT') . $app->icon_key }}">
            </a>
        </p>
        <div class="media-body">
            <h2 class="media-hedding"><a href="{{ route('app', ['id' => $app->app_id]) }}"><?=htmlspecialchars($app->app_title)?></a></h2>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-4 col-md-3 hidden-xs">
            @include('pages.partials.qr_sidebar')
        </div>

        <div class="col-xs-12 col-sm-8 col-md-9">

            <h3>
                <a href="{{ route('package', ['id' => $app->id]) }}">
                    @if ($app->platform == 'iOS')
                        <i class="fa fa-apple"></i>
                    @elseif ($app->platform == 'android')
                        <i class="fa fa-android"></i>
                    @endif

                    &nbsp;{{ $app->title }}
                </a>
            </h3>

            <div class="col-md-8 col-md-offset-1">
                <div class="panel panel-danger">
                    <div class="panel-heading">
                        <h2 class="panel-title">Delete Package</h2>
                    </div>
                    <div class="panel-body">
                        <p>インストールパッケージを削除します。この操作は取り消せません。</p>
                        <p>本当に削除しますか？</p>
                        <div class="text-center">
                            {!! Form::open(['route' => 'delete_package', 'method' => 'post']) !!}
                                {!! Form::hidden('app_id', $app->app_id) !!}
                                {!! Form::hidden('package_id', $app->id) !!}
                                <button class="btn btn-danger"><i class="fa fa-trash-o"></i> Delete</button>
                                <a class="btn btn-default" href="{{ route('package', ['id' => $app->id]) }}"><i class="fa fa-times"></i> Cancel</a>
                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>


            </div>
        </div>

    <div class="visible-xs">
        @include('pages.partials.qr_sidebar')
    </div>

@stop

