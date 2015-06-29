@extends('pages.main')

@section('content')

<div class="media">
  <p class="pull-left">
    <a href="{{ "/app?id={$app->id}" }}">
      <img class="app-icon media-object img-rounded" src="{{ env('AWS_S3_ENDPOINT') . $app->icon_key }}">
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

    <div id="comments">
      <div class="row">
        <div class="col-xs-6">
          <h3>{{ $comment_count }} comments</h3>
        </div>
        <div class="col-xs-6 text-right">
              <a href="{{ url("/app/comment?id={$app->id}") }}" class="btn btn-sm btn-default"><i class="fa fa-pencil"></i> write a comment</a>
        </div>
      </div>
@if($comment_count>0)
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
      <div class="text-right">
        <a href="{{ url("/app/comment?id={$app->id}#comments") }}">read more...</a>
      </div>
@endif
    </div>
    <ul id="pf-nav-tabs" class="nav nav-tabs">
      <li {!! $pf=='android' ? 'class="active"' : '' !!} id="android">
        <a href="{{ "?id={$app->id}&pf=android" }}">Android</a>
      </li>
      <li {!! $pf=='ios'? 'class="active"':'' !!} id="ios">
        <a href="{{ "?id={$app->id}&pf=ios" }}">iOS</a>
      </li>
      <li {!! $pf=='all'? 'class="active"':'' !!} id="all">
        <a href="{{ "?id={$app->id}&pf=all" }}">All</a>
      </li>
    </ul>

    <div id="tag-filter">
      <a id="tag-filter-toggle" class="pull-right badge"><i class="fa fa-angle-double-{{ $filter_open?'up':'down' }}"></i></a>
          <div id="tag-filter-body" style="display: {{ ($filter_open)? 'block': 'none' }}">
@foreach($app->tags as $tag)
        <button id="{{ $tag->id }}" class="btn btn-default {{ in_array($tag->id, $active_tags) ? 'on active' : '' }}" data-toggle="button">
        {{ htmlspecialchars($tag->name) }}
        </button>
@endforeach
      </div>
    </div>

    <table id="package-list" class="table table-hover">
@foreach($packages as $key => $pkg)
      <tr>
        <td class="text-center logo">
          @include('pages/partials/platform_icon')
        </td>
        <td class="package-list-item-info">
          <div class="row">
            <div class="col-xs-12 col-md-7">
              <a class="title" href="{{ url('/package?id='.$pkg->id) }}">{{ htmlspecialchars($pkg->title) }}</a>
@if($pkg->description)
      <p class="text-muted description">{{ $pkg->description }}</p>
@endif
              <span class="info hidden-xs hidden-sm">{{ $pkg->file_size?round($pkg->file_size/1024/1024,1):'--' }} MB, {{ date('Y-m-d H:i', strtotime($pkg->created_at)) }}</span>
            </div>
            <div class="col-xs-12 col-md-5">
@if($pkg->is_file_size_warned)
              <span class="label label-danger">Over {{ $pkg->file_size_limit }} MB</span>
@endif
@foreach($pkg->tags as $tag)
              <span class="label label-default" data="{{ htmlspecialchars($tag->name) }}">{{ htmlspecialchars($tag->name) }}</span>
@endforeach
            </div>
          </div>
      <span class="info visible-xs visible-sm">{{ $pkg->file_size?round($pkg->file_size/1024/1024,1):'--' }} MB</span>
          <span class="info visible-xs visible-sm">{{ date('Y-m-d H:i', strtotime($pkg->created_at)) }}</span>
        </td>
        <td class="text-center">
@if($pkg->is_installed)
          <a class="btn btn-success install-link col-xs-12" href="{{ route('install_package', ['id'=>$pkg->id]) }}"><i class="fa fa-check"></i> Installed</a>
@else
          <a class="btn btn-primary install-link col-xs-12" href="{{ route('install_package', ['id'=>$pkg->id]) }}"><i class="fa fa-download"></i> Install</a>
@endif
        </td>
      </tr>
@endforeach
    </table>

    <ul class="pager">
@if($current_page==1)
      <li class="previous disabled"><span>Previous</span></li>
@else
      <li class="previous"><a href="{{ $prev_page_url }}">Previous</a></li>
@endif

@if($next_page_url)
      <li class="next"><a href="{{ $next_page_url }}">Next</a></li>
@else
      <li class="next disabled"><span>Next</span></li>
@endif
    </ul>

  </div>
</div>

<div class="visible-xs">
  @include('pages/partials/app_infopanel')
</div>

<script type="text/javascript">

$('#tag-filter-toggle').on('click',function(){
  $down = $('i.fa-angle-double-down');
  $up = $('i.fa-angle-double-up');
  if($down){
    $down.removeClass('fa-angle-double-down').addClass('fa-angle-double-up');
  }
  if($up){
    $up.removeClass('fa-angle-double-up').addClass('fa-angle-double-down');
  }
  $('.pager>li>a').each(function(){
    if($up.length>0){
      this.href = this.href.replace(/&filter_open=1/,'');
    }
    else{
      this.href = this.href + '&filter_open=1';
    }
  });

  $('#tag-filter-body').slideToggle('fast');
});

function get_url_param_tabs() {
  var $active_tags = $('#tag-filter-body>button.on');
  if ($active_tags.length>0) {
    var tags = '';
    $active_tags.each(function(i){tags += $active_tags[i].id + '+';});
    return '&tags=' + tags.substring(0, tags.length - 1);
  } else {
    return '';
  }
}

function compose_url() {
  var pf = 'all';
  var $active_pf_tabs = $('#pf-nav-tabs>li.active');
  if ($active_pf_tabs.length>0) {
    pf = $active_pf_tabs[0].id
  }
  var of = '';
  if ($('i.fa-angle-double-up').length>0) {
    of = '&filter_open=1';
  }
  return "<?="id={$app->id}&pf="?>" + pf + get_url_param_tabs() + of;
}

// filter by tag
$('#tag-filter-body>button').on('click',function(){
  if($(this).hasClass('on')){
    $(this).removeClass('on');
  }
  else{
    $(this).addClass('on');
  }
  location.href = '?' + compose_url();
});

$('.package-list-item-info').on('click',function(event){
  $('a',this)[0].click();
});

$('#pf-nav-tabs>li').on('click', function(event){
  if ($('a', this)) {
    location.href = $('a', this)[0].href + get_url_param_tabs();
    event.preventDefault();
  }
});

</script>
@endsection
