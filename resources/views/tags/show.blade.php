@extends('layout')

@section('content')

<div class="row">
    <div class="col-lg-12 margin-tb pb-2">
        <div class="pull-left">
            <h2>#{{ $tag->name }}</h2>
        </div>
        <div class="pull-right">
            <a class="btn btn-primary" href="{{ route('tags.index') }}"> Back</a>
        </div>
    </div>
</div>

@include('drills/list')
@endsection