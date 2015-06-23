@extends('pages.main')

@section('content')
    <div class="media">
        <p class="pull-left">
            <a href="{{ route('app', ['id' => $app->app_id]) }}"> {{-- url("/app?id={$app->getId()}")--}}
                <img class="app-icon media-object img-rounded" src="{{ env('AWS_URL') .  $app->icon_key }}">{{-- $app->getIconUrl() --}}
            </a>
        </p>
        <div class="media-body">
            <h2 class="media-hedding"><a href="{{ route('app', ['id' => $app->app_id]) }}">{{ $app->app_title }}</a></h2>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-4 col-md-3 hidden-xs">
            @include('pages.partials.qr_sidebar')
        </div>

        <div class="col-xs-12 col-sm-8 col-md-9">

            <h3>
                <a href="{{ route('package', ['id' => $app->id]) }}">
                    @if ($app->platform == 'ios')
                        <i class="fa fa-apple"></i>
                    @else
                        <i class="fa fa-android"></i>
                    @endif
                    {{ $app->title }}
                    {{--htmlspecialchars($package->getTitle())--}}
                </a>
            </h3>

            {!! Form::open(['url' => route('save_package'), 'class' => 'form-horizontal']) !!}
            {{--<form class="form-horizontal" method="post" action=""> --}}{{-- <url("/package/edit_commit?id={$package->getId()}") --}}

                <div class="form-group">
                    <label for="title" class="control-label col-md-2 required">Title</label>
                    <div class="col-md-10">
                        <div id="alert-notitle" class="alert alert-danger hidden">
                            タイトルが入力されていません
                        </div>

                        <input type="hidden" name="id" value="{{ $app->id }}">
                        <input type="text" class="form-control" name="title" id="title" value="{{ $app->title }}">
                    </div>
                </div>

                <div class="form-group">
                    <label for="description" class="control-label col-md-2">Description</label>
                    <div class="col-md-10">
                        <textarea class="form-control" row="3" id="description" name="description">{{ $app->description }}</textarea>
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-md-2">Tags</label>
                    <div class="col-md-10">
                        @foreach($all_tags as $tag)
                        <?php $checked = array_key_exists($tag->id, $package_tags) ? 'checked' : ''?>
                        <input type="checkbox" class="hidden" name="tags[]" value="{{ $tag->id }}" {{ $checked }}>
                        <button class="btn btn-default tags" data-toggle="button">{{ $tag->name }}</button>
                        @endforeach

                        <div id="tag-template" class="hidden">
                            <input type="checkbox" class="hidden" name="tags[]" value="">
                            <button class="btn btn-default tags" data-toggle="button"></button>
                        </div>

                        <div class="btn-group">
                            <a id="add-tag-button" class="btn btn-default dropdown-toggle" href="#" data-toggle="dropdown"><i class="fa fa-plus"></i></a>
                            <div id="new-tag-form" class="dropdown-menu">
                                <div class="container">
                                    <input type="text" id="new-tag-name" class="form-control">
                                    <button id="new-tag-create" class="btn btn-primary">Create</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-md-10 col-md-offset-2">
                        <button class="btn btn-primary"><i class="fa fa-save"></i> Save</button>
                        <a class="btn btn-default" href="{{ route('package', ['id' => $app->id]) }}"><i class="fa fa-times"></i> Cancel</a>
                    </div>
                </div>

            {!! Form::close() !!}

        </div>
    </div>

    <div class="visible-xs">
        @include('pages.partials.qr_sidebar')
    </div>

@section('scripts')
    <script type="text/javascript">

        // initialize tags button state
        $('input[name="tags[]"]').each(function(i,val){
            if($(val).prop('checked')){
                $(val).next().addClass('active');
            }
        });
        // toggle tags checkbox
        $('.btn.tags').on('click',function(event){
            $(this).prev().prop('checked',!$(this).hasClass('active'));
        });

        // don't close dropdown
        $('#new-tag-form').click(function(event){
            event.stopPropagation();
        });

        // click create button by enter key
        $('#new-tag-name').keydown(function(event){
            if(event.keyCode==13){
                $('#new-tag-create').click();
                return false;
            }
            return true;
        });

        // focus #new-tag-name form when form opened.
        $('#add-tag-button').on('focus',function(event){
            if($(this).parent().hasClass('open')){
                $('#new-tag-name').focus();
            }
        });

        // create new tag button
        $('#new-tag-create').on('click',function(event){
            var $tagname = $('#new-tag-name');
            var tag = $tagname.val();
            if(tag){
                var $tmpl = $('#tag-template');
                var $c = $tmpl.children().clone(true);

                $($c[0]).attr('value',tag).prop('checked',true);
                $($c[1]).text(tag).addClass('active')

                $tmpl.before($c);
                $tmpl.before(' ');

                $tagname.val(null);
                $c.focus();
            }
            $('.dropdown-toggle').parent().removeClass('open');
            return false;
        });

        // handle enter key on #title
        $('#title').keydown(function(event){
            if(event.keyCode==13){
                $('form').submit();
                return false;
            }
            return true;
        });

        // form validation
        $('form').submit(function(){
            var valid = true;
            $('.alert').addClass('hidden');
            if($('#title').val()==''){
                $('#alert-notitle').removeClass('hidden');
                valid = false;
            }
            return valid;
        });


    </script>
@stop
@stop