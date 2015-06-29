@extends('pages.main')

@section('content')
<div class="page-header">
  <h2 class="headding">Own Applications</h2>
</div>

<div>
<?php $counter = 0; ?>
@foreach($own_apps as $app)
<div class="media app-list-item col-md-6">
  <p class="pull-left">
    <a href="{{ "/app?id={$app->id}" }}">
      <img class="app-icon-sm media-object img-rounded" src="{{ env('AWS_S3_ENDPOINT') . $app->icon_key }}">
    </a>
  </p>
  <div class="media-body">
    <h3 class="media-hedding">
      <a href="{{ "/app?id={$app->id}" }}">
        {{ htmlspecialchars($app->title) }}
      </a>
      <small title="Installed by {{ $app->user_count }} users" class="badge">{{ $app->user_count }}</small>
    </h3>
    <p>
      {{ (strtotime($app->updated_at) > strtotime($app->created_at)) ? 'Last Uploaded: ' . date('Y-m-d H:i',strtotime($app->updated_at)) :'Created: ' . date('Y-m-d H:i',strtotime($app->created_at)) }}
<?php if(!empty($app->latest_user_install) && $app->updated_at > $app->latest_user_install): ?>
      <span class="label label-success">UPDATE</span>
<?php elseif(strtotime($app->updated_at)>strtotime('yesterday')): ?>
      <span class="label label-primary">NEW</span>
<?php endif ?>
    </p>
  </div>
</div>
<?php if((++$counter)%2===0): ?>
</div>
<div class="row">
<?php endif ?>
@endforeach
    <div class="text-center">
        {!! $own_apps->setPath('/myapps/own')->appends(Input::query())->render() !!}
    </div>
</div>

<script type="text/javascript">

$('.notification-toggle button').on('click',function(event){
  var id = $(this).parent().attr('data-app-id');
  var value = $(this).attr('value');
  $.ajax({
    url: "{{ url('/api/notification_setting?id=') }}"+id+"&value="+value,
    type: "POST",
    success: function(data){
      if(data.notify){
         $('[data-app-id="'+id+'"]>button[value="1"]').addClass('active');
         $('[data-app-id="'+id+'"]>button[value="0"]').removeClass('active');
      }
      else{
         $('[data-app-id="'+id+'"]>button[value="1"]').removeClass('active');
         $('[data-app-id="'+id+'"]>button[value="0"]').addClass('active');
      }
    }
  });
});

$('button.delete').on('click',function(event){
  if(confirm("このアプリケーションをインストール済みリストから削除します.\n個々のパッケージのインストール履歴は削除されません.\n削除しますか?")){
    location.href = '{{ url('/myapps/delete?id=') }}' + $(this).attr('data-app-id');
  }
});

$('.app-list-item-info').on('click',function(event){
  $('a',this)[0].click();
});

</script>

@endsection
