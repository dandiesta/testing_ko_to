@extends('pages.main')

@section('content')
    <div class="page-header row">
        <h2 class="headding col-xs-12 col-sm-8">EMLauncher
            <small class="subtitle">Only my APP can shoot it.</small>
        </h2>
        <div id="create-app-button" class="hidden-xs col-sm-4 text-right">
            <a class="btn btn-default" href="#"><i class="fa fa-plus"></i> New Application</a>
        </div>
    </div>

    <div class="row">
        <?php $counter = 0; ?>
        {{--@foreach($applications as $app)--}}
        <div class="media app-list-item col-md-6">
            <p class="pull-left">
                {{--Link to App Page--}}
                <a href="#">
                    {{--App's Icon--}}
                    <img class="app-icon-sm media-object img-rounded" src="https://s3-ap-southeast-1.amazonaws.com/app-klab-reserve/globe_telecom/ahue.png">
                </a>
            </p>
            <div class="media-body">
                <h3 class="media-hedding">
                    {{--App Title; Also a link--}}
                    <a href="#">The Derpening</a>
                    {{--Comments Count--}}
                    {{--@if(isset($comments[$app->getId()]) && $comments[$app->getId()]>0)--}}
                    <div class="balloon">
                        <div title="App_Id comments">420</div>
                    </div>
                    {{--@endif--}}
                    {{--No of Installs--}}
                    <small title="Installed by theCountForSomeReason users" class="badge">1337</small>
                </h3>
                <p>
                    {{--{{--}}
                    {{--Labels for dates--}}
                    {{--$upload_time = $app->getLastUpload();--}}
                    {{--$update_time = $upload_time?:$app->getCreated();--}}
                    {{--}}--}}
                    {{--{{ ($upload_time)?'last uploaded':'created' }}: {{ date('Y-m-d H:i',strtotime($update_time)) }}--}}

                    {{--Status Labels--}}
                    {{--@if($login_user->getAppInstallDate($app) && $upload_time>$login_user->getAppInstallDate($app))--}}
                    <span class="label label-success">UPDATE</span>
                    {{--@elseif(strtotime($update_time)>strtotime('yesterday'))--}}
                    <span class="label label-primary">NEW</span>
                    {{--@endif--}}
                </p>
            </div>
        </div>
        @if((++$counter)%2===0)
    </div>
    <div class="row">
        @endif
        {{--@endforeach--}}
    </div>

    {{--New Application Button--}}
    <div id="create-app-button" class="col-xs-12 visible-xs text-left">
        <a class="btn btn-default" href="<?=url('/app/new')?>"><i class="fa fa-plus"></i> New Application</a>
    </div>

    {{--Pagination thingy--}}
    {{--<div class="text-center">--}}
        {{--{{('paging',array('urlbase'=>url('/')))}}--}}
    {{--</div>--}}

    {{--<script type="text/javascript">--}}

        {{--$('.app-list-item').on('click',function(event){--}}
            {{--$('a',this)[0].click();--}}
        {{--});--}}

    {{--</script>--}}
@stop
