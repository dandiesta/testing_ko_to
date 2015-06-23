<div class="list-group">
@if($app->is_owner)
  <div class="list-group-item">
    <ul class="nav nav-pills nav-stacked">
      <li {!! ($action==='upload') ? 'class="active"' : '' !!} >
        <a href="{{ url("/app/upload?id={$app->id}") }}"><i class="fa fa-upload"></i> Upload</a>
      </li>
      <li {{ ($action==='preferences')?' class="active"':'' }}>
        <a href="{{ url("/app/preferences?id={$app->id}") }}"><i class="fa fa-wrench"></i> Preferences</a>
      </li>
    </ul>
  </div>
@endif
  <div class="list-group-item">
    <dl>
    @if($app->last_upload)
      <dt>last upload</dt>
      <dd><?=$app->last_upload?></dd>
@endif
@if($app->last_commented)
      <dt>last comment</dt>
      <dd><?=$app->last_commented?></dd>
@endif
      <dt>created</dt>
      <dd><?=$app->created_at?></dd>
      <dt>install user</dt>
@if($app->is_owner)
      <dd>
        <div class="dropdown">
          <a class="dropdown-toggle" id="install-user-count" data-toggle="dropdown">
            <?=$app->install_user_count?>
          </a>
          <ul class="dropdown-menu" role="menu" aria-labelledby="install-user-count">
@foreach($app->install_user as $u)
            <li role="presentation"><a role="menuitem" tabindex="-1"><?=$u->mail?></a></li>
@endforeach
          </ul>
        </div>
      </dd>
@else
      <dd><?=$app->install_user_count?></dd>
@endif
@if($app->repository)
      <dt>repository</dt>
@if(preg_match('|^https?://([^/]*)/(.*)$|',$app->repository,$m))
      <dd>
        <a target="_blank" href="<?=htmlspecialchars($app->repository)?>" class="repository-link">
@if($m[1]==='github.com')
          <i class="fa fa-github"></i>
          <?=htmlspecialchars($m[2]);?>
@elseif(strpos($m[1],'github')!==false)
          <i class="fa fa-github-square"></i>
          <?=htmlspecialchars($m[2]);?>
@elseif(strpos($m[1],'bitbucket')!==false)
          <i class="fa fa-bitbucket"></i>
          <?=htmlspecialchars($m[2]);?>
@else
          <?=htmlspecialchars("{$m[1]}/{$m[2]}")?>
@endif

        </a>
      </dd>
@else
      <dd><input type="text" class="form-control" readonly="readonly" value="<?=htmlspecialchars($app->repository)?>"></dd>
@endif
@endif
      <dt>owners</dt>
@foreach($app->owners as $owner)
      <dd><a href="mailto:<?=$owner->owner_email?>"><?=$owner->owner_email?></a></dd>
@endforeach
    </dl>
  </div>
  <div class="list-group-item">
    <div class="text-center">
      <p>link to this app</p>
      <img src="https://chart.googleapis.com/chart?chs=150&cht=qr&chl=<?=urlencode(url("/app?id={$app->id}"))?>">
    </div>
  </div>
</div>
