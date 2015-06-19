@extends('pages.main')

@section('content')
<div class="media">
    <p class="pull-left">
        <a href=""> {{-- /app?id={$app->getId()} --}}
            <img class="app-icon media-object img-rounded" src="{{ asset('apple-touch-icon.png') }}"> {{-- $app->getIconUrl() --}}
        </a>
    </p>
    <div class="media-body">
        <h2 class="media-hedding"><a href="">Title goes here{{-- htmlspecialchars($app->getTitle()) --}}</a></h2> {{-- url("/app?id={$app->getId()}") --}}
    </div>
</div>

<div class="row">
    <div class="col-sm-4 col-md-3 hidden-xs">
        {{-- block('pkg_infopanel') --}}asdf
    </div>

    <div class="col-xs-12 col-sm-8 col-md-9">

        <div class="row">
            <div class="col-xs-7">
                <h3>
                    <a href=""> {{-- url("/package?id={$package->getId()}") --}}
                        {{-- block('platform_icon') --}}asf
                        {{-- htmlspecialchars($package->getTitle()) --}}
                    </a>
                </h3>
                <p>
                    {{-- nl2br(htmlspecialchars($package->getDescription())) --}}
                    Built at:2015-06-19_10-34-21
                    Branch:origin/1.20.1/develop
                    e02435713587ce319b7e72c80eedc0d5cfede5b0
                    https://redmine.cyscorpions.com/projects/lods-eu-ios/repository/revisions/e02435713587ce319b7e72c80eedc0d5cfede5b0
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
            {{-- if($package->isFileSizeWarned()): --}}
                <span class="label label-danger">Over 100{{-- $package->getFileSizeLimitMB() --}} MB</span>
            {{-- endif --}}
            {{-- foreach($package->getTags() as $tag): --}}
                <span class="label label-default">yo{{-- htmlspecialchars($tag->getName()) --}}</span>
            <span class="label label-default">hey{{-- htmlspecialchars($tag->getName()) --}}</span>
            {{-- endforeach --}}
        </p>

        <dl class="dl-horizontal">
            <dt>Platform</dt>
            <dd>{{-- block('platform_icon',array('with_name'=>true)) --}} ios (with image dapat to)</dd>
            <dt>Original name</dt>
            <dd>{{-- $package->getOriginalFileName()?:'--------.'.pathinfo($package->getBaseFileName(),PATHINFO_EXTENSION) --}}asdf</dd>
            <dt>File size</dt>
            <dd>{{-- $package->getFileSize()?number_format($package->getFileSize()):'-' --}}110,000,000 bytes</dd>
            <dt>Install user</dt>
            {{-- if($app->isOwner($login_user)): --}}
                <dd>
                    <div class="dropdown">
                        <a class="dropdown-toggle" id="install-user-count" data-toggle="dropdown">
                        </a>
                        <ul class="dropdown-menu" role="menu" aria-labelledby="install-user-count">
                             {{--foreach($package->getInstallUsers() as $mail):--}}
                                <li role="presentation"><a role="menuitem" tabindex="-1"> {{-- $mail --}} </a></li>
                             {{--endforeach--}}
                        </ul>
                    </div>
                </dd>
            {{-- else: --}}
                <dd>{{-- $package->getInstallCount() --}}1234</dd>
            {{-- endif --}}
            <dt>Uploaded</dt>
            <dd>{{-- $package->getCreated() --}}2015-06-19 11:01:37</dd>
            <dt>Owners</dt>
            {{-- foreach($app->getOwners() as $owner): --}}
                <dd><a href="mailto:{{-- $owner->getOwnerMail()?> --}} {{-- $owner->getOwnerMail() --}}">ASD</a></dd>
            {{-- endforeach --}}
        </dl>

        <div class="col-xs-12 col-sm-9">
            <p class="text-center">
                <a class="btn btn-default" href=""><i class="fa fa-bolt"></i> Create Install Token</a> {{-- url("/package/create_token?id={$package->getId()}") --}}
            </p>
        </div>

    </div>
</div>

<div class="visible-xs">
    {{-- block('pkg_infopanel') --}}
</div>

@stop

