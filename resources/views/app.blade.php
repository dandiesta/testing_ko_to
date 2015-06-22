@extends('pages.main')

@section('content')
    {{ $details->title }}
    <br ><br ><br >
    <b>Packages:</b><br>
    @foreach($packages as $package)
    <a href="{{ route('package', ['id' => $package->id]) }}" >{{ $package->title }}</a><br >
    @endforeach
@stop