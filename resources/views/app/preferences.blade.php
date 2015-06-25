@extends('pages.main')

@section('content')

<div class="media">
  <p class="pull-left">
    <a href="{{ "/app?id={$app->id}" }}">
      <img class="app-icon media-object img-rounded" src="{{ env('AWS_S3_ENDPOINT') . $app->icon_key }}">
    </a>
  </p>
  <div class="media-body">
    <h2 class="media-hedding"><a href="<?=url("/app?id={$app->id}")?>"><?=htmlspecialchars($app->title)?></a></h2>
    <p><?=htmlspecialchars($app->description)?></p>
  </div>
</div>

<div class="row">
  <div class="col-sm-4 col-md-3 hidden-xs">
      @include('pages/partials/app_infopanel')
  </div>

  <div class="col-xs-12 col-sm-8 col-md-9">

    <div class="well">
        {!! Form::open(['url' => '', 'class' => 'form-inline', 'id' => 'refresh-apikey']) !!} {{-- url('/app/preferences_refresh_apikey') --}}
        <legend>API Key</legend>
        <input type="hidden" name="id" value="{{ $app->id }}">
        <div class="form-group">
          <label class="sr-only" for="api-key">API Key</label>
          <input type="text" id="api-key" name="api-key" class="form-control" readonly="readonly" value="{{ htmlspecialchars($app->api_key) }}">
        </div>
        <button id="submit-refresh-apikey" type="submit" class="btn btn-warning"><i class="fa fa-refresh"></i> Refresh</button>
        <div class="help-block">
          APIを利用するために必要なキーです.
          詳細は<a href="<?=url('/doc/api')?>">APIドキュメント</a>を参照してください.
        </div>
      {!! Form::close() !!}
    </div>

    <div class="well">
        {!! Form::open(['url' => route('update_preferences'), 'class' => 'form-horizontal', 'enctype' => 'multipart/form-data']) !!}
        <input type="hidden" name="id" value="{{ $app->id }}">
        <legend>Edit Information</legend>

        @if(!$errors->isEmpty() && !empty($errors->all()))
            <div id="alert-notitle" class="alert alert-danger" style="margin-bottom: 4px">
                <p>
                    @foreach($errors->all() as $msg)
                        &nbsp; {{ $msg }} <br>
                    @endforeach
                </p>
            </div>
        @endif

        <div class="row">
          <div class="col-lg-10 col-md-9 col-xs-12">
            <div class="form-group">
	          <label for="title" class="control-label col-md-3 required">Title</label>
              <div class="col-md-9">
                <input class="form-control" type="text" id="title" name="title" value="{{ htmlspecialchars($app->title) }}">
              </div>
            </div>

            <div class="form-group">
              <label for="icon-selector" class="control-label col-md-3">Icon</label>
              <div class="col-md-9">
                <div id="alert-icon-size-limit" class="alert alert-danger hidden">
                  画像ファイルサイズが大きすぎます
                </div>
                  {!! Form::file('icon-selector', ['id' => 'icon-selector', 'class' => 'hidden']) !!}
                  <div class="input-group"  id="input-group-icon">
                      <input type="text" class="form-control droparea" id="icon-text" readonly="readonly">
                      <a id="icon-browse" class="input-group-addon btn btn-default">Browse</a>
                  </div>
              </div>
            </div>
          </div>

          <div class="col-lg-2 col-md-3 hidden-sm hidden-xs text-center">
            <img id="icon-preview" class="img-thumbnail droparea" style="width:96px;height:96px;" src="{{ env('AWS_URL') . $app->icon_key }}">
          </div>
        </div>

        <div class="row">
          <div class="col-lg-10 col-md-9 col-xs-12">
            <div class="form-group">
              <label for="description" class="control-label col-md-3">Description</label>
              <div class="col-md-9">
                <textarea class="form-control" row="3" id="description" name="description">{{ htmlspecialchars($app->description) }}</textarea>
              </div>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-lg-10 col-md-9 col-xs-12">
            <div class="form-group">
              <label for="repository" class="control-label col-md-3">Repository</label>
              <div class="col-md-9">
                <input type="text" class="form-control" id="repository" name="repository" value="{{ htmlspecialchars($app->repository) }}">
              </div>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-lg-10 col-md-9 col-xs-12">
            <div class="form-group">
              <div class="col-md-9 col-md-offset-3">
                <button class="btn btn-primary"><i class="fa fa-save"></i> Update</button>
              </div>
            </div>
          </div>
        </div>
      {!! Form::close() !!}
    </div>

    <div class="well">
        {!! Form::open(['url' => route('delete_tags_preferences'), 'class' => 'form-horizontal', 'id' => 'delete-tags']) !!}
        <input type="hidden" name="id" value="{{ $app->id }}">
        <legend>Delete Tags</legend>

        <div class="form-group">
          <div class="col-xs-12">
              @foreach($app->all_tags as $tag)
                  <input type="checkbox" class="hidden" name="tags[]" value="{{ htmlspecialchars($tag->id) }}">
                  <button class="btn btn-default delete-tags" data-toggle="button">{{ htmlspecialchars($tag->name) }}</button>
              @endforeach
          </div>
        </div>

        <div class="form-group">
          <div class="col-xs-12">
          <button class="btn btn-danger"><i class="fa fa-trash-o"></i> Delete</button>
          </div>
        </div>

      {!! Form::close() !!}
    </div>

    <div class="well">
        {!! Form::open(['url' => route('update_owners_preferences'), 'id' => 'owners', 'class' => 'form-horizontal']) !!}
        <input type="hidden" name="id" value="{{ $app->id }}">
        <legend>Owners</legend>

        <div class="form-group">
          <div class="col-xs-12">
            <div class="form-control" readonly="readonly">
              {{ Auth::user()->mail }}
            </div>
          </div>
        </div>

        @foreach($app->owners as $owner)
        <?php if($owner->owner_email == Auth::user()->mail) continue; ?>
                <div class="form-group edit-owner">
                  <div class="col-xs-12">
                    <div class="form-control" readonly="readonly">
                      <button type="button" class="close pull-left"><i class="fa"></i></button>
                      <span><?=htmlspecialchars($owner->owner_email)?></span>
                      <input type="hidden" name="owners[]" value="<?=htmlspecialchars($owner->owner_email)?>">
                    </div>
                  </div>
                </div>
        @endforeach

        <div id="owner-form-template" class="form-group edit-owner add hidden">
          <div class="col-xs-12">
            <div class="form-control" readonly="readonly">
              <button type="button" class="close pull-left"><i class="fa"></i></button>
              <span></span>
              <input type="hidden">
            </div>
          </div>
        </div>

        <div id="add-owner" class="form-group">
          <div class="col-xs-12">
            <button class="close"><i class="fa fa-plus"></i></button>
            <input type="text" class="form-control" name="owners[]">
          </div>
        </div>

        <div class="form-group">
          <div class="col-xs-12">
            <button class="btn btn-primary"><i class="fa fa-save"></i> Update</button>
          </div>
        </div>

      {!! Form::close() !!}
    </div>

  </div>
</div>

<div class="visible-xs">
    @include('pages/partials/app_infopanel')
</div>

@section('scripts')
    <script type="text/javascript">

// API Key
$('#submit-refresh-apikey').on('click',function(){
  return confirm('現在のAPI Keyは使用できなくなります。\nよろしいですか？');
});

// Edit Information
$(document).on('drop dragover',function(e){e.preventDefault()});

$('#icon-browse').on('click',function(event){
  $('#icon-selector').click();
  return false;
});
$('#icon-preview').on('click',function(event){
  $('#icon-selector').click();
  return false;
});

function setIconFile(file){
  $('#icon-data').val(null);
  $('#icon-text').val(null);
  $('#icon-preview').attr('src',"{{ env('AWS_URL') . $app->icon_key }}");
  $('#alert-icon-size-limit').addClass('hidden');

  if(!file || !file.type.match('^image/(png|gif|jpeg)$')){
    return false;
  }
  if(file.size > 1000000){
    $('#alert-icon-size-limit').removeClass('hidden');
    return false;
  }

  var reader = new FileReader;
  reader.onload = function(e){
    $('#icon-data').val(e.target.result);
    $('#icon-preview').attr('src',e.target.result);
  };
  reader.readAsDataURL(file);
  $('#icon-text').val(file.name);
}

$('#icon-preview').on('drop',function(event){
  var file = event.originalEvent.dataTransfer.files[0];
  $('.droparea').removeClass('dragover');
  return setIconFile(file);
});
$('#input-group-icon').on('drop',function(event){
  var file = event.originalEvent.dataTransfer.files[0];
  $('.droparea').removeClass('dragover');
  return setIconFile(file);
});
$('.droparea').on('dragenter',function(event){
  $('.droparea').removeClass('dragover');
  $(this).addClass('dragover');
});
$('.droparea').on('dragleave',function(event){
  $(this).removeClass('dragover');
});

$('#icon-selector').on('change',function(event){
  var file = event.target.files[0];
  return setIconFile(file);
});

$('#edit-info').submit(function(){
  var valid = true;
  $('.alert').addClass('hidden');
  if($('#title').val()==''){
    $('#alert-notitle').removeClass('hidden');
    valid = false;
  }
  return valid;
});

// initialize tags button state
$('input[name="tags[]"]').each(function(i,val){
  if($(val).prop('checked')){
    $(val).next().addClass('active');
  }
});
// toggle tags checkbox
$('.btn.delete-tags').on('click',function(event){
  $(this).prev().prop('checked',!$(this).hasClass('active'));
});

// owner form
$('.edit-owner button').on('click',function(event){
  var $parent = $(this).parent().parent().parent();
  if($parent.hasClass('add')){
    $parent.hide('fast',function(){$parent.remove();});
  }
  else if($parent.hasClass('delete')){
    $parent.removeClass('delete');
    $('input',$parent).attr('name','owners[]');
  }
  else{
    $parent.addClass('delete')
    $('input',$parent).removeAttr('name');
  }
  return false;
});

// initialize form
$('.edit-owner').each(function(i,val){
  $('input',val).val($('span',val).text());
});

$('#add-owner button').on('click',function(event){
  var $template = $('#owner-form-template');
  var $clone = $template.clone(true);
  var new_owner = $(this).next().val();
  $('span',$clone).text(new_owner);
  $('input',$clone).attr('name','owners[]');
  $('input',$clone).val(new_owner);
  $clone.removeClass('hidden');
  $clone.removeAttr('id');
  $template.before($clone);
  $(this).next().val(null);
  return false;
});

</script>
@stop
@endsection
