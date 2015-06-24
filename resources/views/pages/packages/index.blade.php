@extends('pages.main')

@section('content')
<div class="media">
    <p class="pull-left">
        <a href="{{ route('app', ['id' => $app->app_id]) }}"> {{-- /app?id={$app->getId()} --}}
            <img class="app-icon media-object img-rounded" src="{{ env('AWS_S3_ENDPOINT') . '/app-icons/'. $app->icon_key }}"> {{-- $app->getIconUrl() --}}
        </a>
    </p>
    <div class="media-body">
        <h2 class="media-hedding"><a href="{{ route('app', ['id' => $app->app_id]) }}">{{ $app->app_title }}</a></h2> {{-- url("/app?id={$app->getId()}") --}}
    </div>
</div>

<div class="row">
    <div class="col-sm-4 col-md-3 hidden-xs">
        @include('pages.partials.qr_sidebar')
    </div>

    <div class="col-xs-12 col-sm-8 col-md-9">

        <div class="row">
            <div class="col-xs-7">
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
                <p>
                    {{ $app->description }}
                </p>
            </div>
            <div class="col-xs-5">
                {{-- if($login_user->getPackageInstalledDate($package)):  --}}
                    {{--<a href="" class="btn btn-success col-xs-12"><i class="fa fa-check"></i> Installed</a> --}}{{-- $package->getInstallUrl() --}}
                    {{--<dl id="installed-date">--}}
                        {{--<dt>Instaled at</dt>--}}
                        {{--<dd>--}}{{-- $login_user->getPackageInstalledDate($package) --}}{{--</dd>--}}
                    {{--</dl>--}}
                {{-- else: --}}
                    <a href="" class="btn btn-primary col-xs-12"><i class="fa fa-download"></i> Install</a> {{-- $package->getInstallUrl() --}}
                {{-- endif --}}
            </div>
        </div>

        <p>
             @if(floor(($app->file_size/1024)/1024) > 0)
                <span class="label label-danger">Over {{ \App\Package::isFileSizeWarned($app->file_size) }} {{-- $package->getFileSizeLimitMB() --}} MB</span>
             @endif

             @foreach($tags as $tag)
                <span class="label label-default">{{ $tag->name }}</span>
             @endforeach
        </p>

        <dl class="dl-horizontal">
            <dt>Platform</dt>
            <dd>
                @if ($app->platform == 'ios')
                    <i class="fa fa-apple"></i> iOS
                @elseif($app->platform == 'android')
                    <i class="fa fa-apple"></i> Android
                @endif
            </dd>
            <dt>Original name</dt>
            <dd>{{ $app->original_file_name }}</dd>
            <dt>File size</dt>
            <dd> {{ $app->file_size ? number_format($app->file_size):'-' }} bytes</dd>
            <dt>Installed user</dt>
{{--             @if($app->isOwner($login_user)):--}}
                {{--<dd>--}}
                    {{--<div class="dropdown">--}}
                        {{--<a class="dropdown-toggle" id="install-user-count" data-toggle="dropdown">--}}
                        {{--</a>--}}
                        {{--<ul class="dropdown-menu" role="menu" aria-labelledby="install-user-count">--}}
                             {{--foreach($package->getInstallUsers() as $mail):--}}
                                {{--<li role="presentation"><a role="menuitem" tabindex="-1"> --}}{{-- $mail --}}{{-- </a></li>--}}
                             {{--endforeach--}}
                        {{--</ul>--}}
                    {{--</div>--}}
                {{--</dd>--}}
            {{-- else: --}}
                <dd>{{ $user_count }}</dd>
            {{-- endif --}}
            <dt>Uploaded</dt>
            <dd>{{-- $package->getCreated() --}}{{ date('Y-m-d H:i:s', strtotime($app->created_at)) }}</dd>
            <dt>Owners</dt>
            {{-- foreach($app->getOwners() as $owner): --}}
                <dd><a href="mailto:{{-- $owner->getOwnerMail()?> --}} {{-- $owner->getOwnerMail() --}}">ASD</a></dd>
            {{-- endforeach --}}
        </dl>

        {{--<div class="col-xs-12 col-sm-9">--}}
            {{--<p class="text-center">--}}
                {{--<a class="btn btn-default" href=""><i class="fa fa-bolt"></i> Create Install Token</a> --}}{{-- url("/package/create_token?id={$package->getId()}") --}}
            {{--</p>--}}
        {{--</div>--}}

    </div>
</div>

<div class="visible-xs">
    @include('pages.partials.qr_sidebar')
</div>

@stop

