@extends('layout')

@section('content')
<div class="row">
    <h2>Drills</h2>

    <div class="d-flex justify-content-between align-items-center">
        <div>
            <form action="{{ url('drills/search') }}" method="GET" id="searchForm">
                @include('session_drills/search')
            </form>
        </div>
        <div>
            <a class="btn btn-success" href="{{ route('drills.create') }}"> Create New Drill</a>
        </div>
    </div>
</div>

@if ($message = Session::get('success'))
<div class="alert alert-success">
    <p>{{ $message }}</p>
</div>
@endif
<br />
@include('drills/list')
@endsection