@extends('pages.main')

@section('content')
<div class="page-header">
  <h2 class="headding">Installed Applications</h2>
</div>

<div>
  <table id="app-list" class="table table-hover">

    <tr class="hidden-xs">
      <th></th>
      <th>title</th>
      <th>last upload</th>
      <th>notification</th>
      <th>delete</th>
    </tr>

@foreach($installed_apps as $app)
    <tr>
      <td class="text-center icon">
        <a href="{{ url('/app?id='.$app->id) }}"><img src="{{ env('AWS_S3_ENDPOINT') . $app->icon_key }}"></a>
      </td>

      <td colspan="2">
        <div class="row app-list-item-info">
          <div class="col-xs-12 col-sm-6">
            <a class="title" href="{{ "/app?id={$app->id}" }}">{{ htmlspecialchars($app->title) }}</a>
          </div>
          <div class="col-xs-12 col-sm-6">

            {{ date('Y-m-d H:i',strtotime((strtotime($app->updated_at) > strtotime($app->created_at)) ? $app->updated_at : $app->created_at)) }}
@if($app->latest_user_install && $app->updated_at > $app->created_at)
            <span class="label label-success">UPDATE</span>
@elseif(strtotime($app->updated_at)>strtotime('yesterday'))
            <span class="label label-primary">NEW</span>
@endif
          </div>
        </div>
        <div class="row xs-buttons visible-xs">
          <b>Notification:</b>
          <div class="btn-group btn-group-sm notification-toggle" data-app-id="{{ $app->id }}">
            <button class="btn btn-default{{ $app->notify ?' active':'' }}" value="1">ON</button>
            <button class="btn btn-default{{ $app->notify ?'':' active' }}" value="0">OFF</button>
          </div>
          <div class="pull-right container">
            <button class="btn btn-danger btn-sm delete" data-app-id="{{ $app->id }}"><i class="fa fa-trash-o"></i> Delete</button>
          </div>
        </div>
      </td>

      <td class="text-center hidden-xs">
        <div class="btn-group notification-toggle" data-app-id="{{ $app->id }}">
          <button class="btn btn-default{{ $app->notify ?' active':'' }}" value="1">ON</button>
          <button class="btn btn-default{{ $app->notify ?'':' active' }}" value="0">OFF</button>
        </div>
      </td>

      <td class="text-center hidden-xs">
        <button class="btn btn-danger delete" data-app-id="{{ $app->id }}"><i class="fa fa-trash-o"></i></button>
      </td>
    </tr>
@endforeach
  </table>
    <div class="text-center">
        {!! $installed_apps->setPath('/myapps/installed')->appends(Input::query())->render() !!}
    </div>
</div>

<script type="text/javascript">

$('.notification-toggle button').on('click',function(event){
  var id = $(this).parent().attr('data-app-id');
  var value = $(this).attr('value');
  $.ajax({
    url: "{!! url('/api/notification_setting?id=') !!}"+id+"&value="+value,
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
    location.href = '{!! url("/myapps/delete?id=") !!}' + $(this).attr('data-app-id');
  }
});

$('.app-list-item-info').on('click',function(event){
  $('a',this)[0].click();
});

</script>
@endsection
