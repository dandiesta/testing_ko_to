@extends('pages.main')

@section('content')
<div id="documentation">

    <ol class="breadcrumb">
        <li>Documentations</li>
        <li class="active">API</li>
    </ol>

    <div class="page-header">
        <h2><i class="fa fa-puzzle-piece"></i> API</h2>
    </div>

    <div class="list-group">
        <a href="{{ route('docs', ['api_upload']) }}" class="list-group-item">
            <h3 class="list-group-item-heading">Upload API</h3>
            <p class="list-group-item-text">Upload a package file.</p>
        </a>
        <a href="{{ route('docs', ['api_package_list']) }}" class="list-group-item">
            <h3 class="list-group-item-heading">Package List API</h3>
            <p class="list-group-item-text">Get package list.</p>
        </a>
        <a href="{{ route('docs', ['api_delete']) }}" class="list-group-item">
            <h3 class="list-group-item-heading">Delete API</h3>
            <p class="list-group-item-text">Delete a package.</p>
        </a>
        <a href="{{ route('docs', ['api_create_token']) }}" class="list-group-item">
            <h3 class="list-group-item-heading">Create Token API</h3>
            <p class="list-group-item-text">Create Token and return install url.</p>
        </a>
    </div>


</div>
@endsection