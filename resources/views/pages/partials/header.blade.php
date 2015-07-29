<div class="media">
    <p class="pull-left">
        <a href="{{ route('app', ['id' => $app->app_id]) }}"> {{-- /app?id={$app->getId()} --}}
            <img class="app-icon media-object img-rounded" src="{{ env('AWS_S3_ENDPOINT') . $app->icon_key }}"> {{-- $app->getIconUrl() --}}
        </a>
    </p>
    <div class="media-body">
        <h2 class="media-hedding"><a href="{{ route('app', ['id' => $app->app_id]) }}">{{ $app->app_title }}</a></h2> {{-- url("/app?id={$app->getId()}") --}}
        <p>{{ nl2br(htmlspecialchars($app->description)) }}</p>
    </div>
</div>