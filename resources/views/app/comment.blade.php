@extends('pages.main')

@section('content')

<div class="media">
  <p class="pull-left">
    <a href="{{ "/app?id={$app->id}" }}">
      <img class="app-icon media-object img-rounded" src="{{ env('AWS_URL') . $app->icon_key }}">
    </a>
  </p>
  <div class="media-body">
    <h2 class="media-hedding"><a href="{{ "/app?id={$app->id}" }}">{{ htmlspecialchars($app->title) }}</a></h2>
    <p>{{ nl2br(htmlspecialchars($app->description)) }}</p>
  </div>
</div>

<div class="row">
  <div class="col-sm-4 col-md-3 hidden-xs">
    @include('pages/partials/app_infopanel')
  </div>

  <div class="col-xs-12 col-sm-8 col-md-9">

    <div id="comment-form">
        {!! Form::open(array('url' => url('/app/post_comment'), 'class'=> 'form-horizontal')) !!}
            <div id="alert-nomessage" class="alert alert-danger hidden">
              コメントが入力されていません
            </div>
            {!! Form::hidden('id', $app->id) !!}

        <textarea name="message" class="form-control" rows="3"></textarea>
        <div class="controls text-right">
          <label for="package_id">Target package</label>
@if(count($install_packages)>0)
          <select name="package_id" class="form-control">
@foreach($install_packages as $pkg)
            <option value="{{ $pkg->id }}">
              @include('pages/partials/platform_icon')
              -
              {{ $pkg->title }}</option>
@endforeach
          </select>
@else
          <select name="package_id" class="form-control" disabled="disabled">
            <option value="0" selected="selected">No package installed</option>
          </select>
@endif
          <button name="submit" class="btn btn-primary"><i class="fa fa-pencil"></i> Comment</button>
        </div>
        {!! Form::close() !!}
    </div>

    <div id="comments">
      <h3>{{ $comment_count }} comments</h3>
      <ul class="list-group">
@foreach($top_comments as $c)
<?php
    $pkg = ($c->package_id)? $commented_package[$c->package_id]: null;
?>
        <li class="list-group-item">
          <dl>
            <dt><a href="{{ url("/app/comment?id={$app->id}#comment-{$c->number}") }}?>">{{ $c->number }}</a></dt>
            <dd>{{ htmlspecialchars($c->message) }}</dd>
          </dl>
          <div class="text-right">
@if($pkg)
            <a href="{{ url("/package?id={$pkg->id}") }}">
            @include('pages/partials/platform_icon')
            {{ htmlspecialchars($pkg->title) }}</a>
@else
            <span>No package installed</span>
@endif
            ({{ date('Y-m-d H:i', strtotime($c->created_at)) }})
          </div>
        </li>
@endforeach
      </ul>
    </div>

    <div class="text-center">
        {!! $top_comments->setPath('/app/comment')->appends(Input::query())->render() !!}
    </div>


  </div>
</div>

<div class="visible-xs">
  @include('pages/partials/app_infopanel')
</div>

<script type="text/javascript">

$('#comment-form form').submit(function(){
 var msg = $('textarea[name="message"]',this).val();
 if(msg.length==0){
   $('#alert-nomessage').removeClass('hidden');
   return false;
 }
 return true;
});


</script>
@endsection
