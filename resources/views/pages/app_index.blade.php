@extends('pages.main')

@section('content')
    <div class="page-header row">
        <h2 class="headding col-xs-12 col-sm-8">EMLauncher
            <small class="subtitle">Only my APP can shoot it.</small>
        </h2>
        <div id="create-app-button" class="hidden-xs col-sm-4 text-right">
            <a class="btn btn-default" href="{{ route('new_app') }}"><i class="fa fa-plus"></i> New Application</a>
        </div>
    </div>

    <div class="row">
        <?php $counter = 0; ?>
        @foreach($applications as $app)
        <div class="media app-list-item col-md-6">
            <p class="pull-left">
                {{--Link to App Page--}}
                <a href="#">
                    {{--App's Icon--}}
                    <img class="app-icon-sm media-object img-rounded" src="{{ env('AWS_S3_ENDPOINT') . '/app-icons/'. $app->icon_key }}">
                </a>
            </p>
            <div class="media-body">
                <h3 class="media-hedding">
                    {{--App Title; Also a link--}}
                    <a href="{{ route('app', ['id' => $app->id]) }}">{{ $app->title }}</a>
                    {{--Comments Count--}}
                    @if(!empty($app->comment_count) && $app->comment_count > 0)
                    <div class="balloon">
                        <div title="App_Id comments">{{ $app->comment_count }}</div>
                    </div>
                    @endif
                    {{--No of Installs--}}
                    <small title="Installed by theCountForSomeReason users" class="badge">{{ $app->user_count }}</small>
                </h3>
                <p>
                    {{ (strtotime($app->updated_at) > strtotime($app->created_at)) ? "Updated: " . date('Y-m-d H:i', strtotime($app->updated_at)) : "Created: " . date('Y-m-d H:i', strtotime($app->created_at))}}

                    {{--Status Labels--}}
                    @if(!empty($app->latest_user_install) && (strtotime($app->updated_at) > strtotime($app->latest_user_install)))
                    <span class="label label-success">UPDATE</span>
                    @elseif(strtotime($app->updated_at)>strtotime('yesterday'))
                    <span class="label label-primary">NEW</span>
                    @endif
                </p>
            </div>
        </div>
        @if((++$counter)%2===0)
    </div>
    <div class="row">
        @endif
        @endforeach
    </div>

    {{--New Application Button--}}
    <div id="create-app-button" class="col-xs-12 visible-xs text-left">
        <a class="btn btn-default" href="{{ route('new_app') }}"><i class="fa fa-plus"></i> New Application</a>
    </div>

    {{--Pagination thingy--}}
    <div class="text-center">
        {!! $applications->render() !!}
    </div>

    {{--<script type="text/javascript">--}}

        {{--$('.app-list-item').on('click',function(event){--}}
            {{--$('a',this)[0].click();--}}
        {{--});--}}

    {{--</script>--}}
@stop
