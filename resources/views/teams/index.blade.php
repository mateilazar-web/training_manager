@extends('layout')

@section('content')

@php
use App\Enums\TeamRoleAssignStatus;
@endphp

<div class="row">
    <div class="col-lg-12">
        <div class="pull-left">

        </div>

        @if (!isset($team))
        <div class="pull-right">
            <a class="btn btn-success" href="{{ route('teams.create') }}"> Create New Team</a>
        </div>
        @endif

    </div>
</div>

@if ($message = Session::get('success'))
<div class="alert alert-success">
    <p>{{ $message }}</p>
</div>
@endif
<br />

@if ($listType == TeamRoleAssignStatus::Unassigned)
@include('teams/request_access')
@elseif ($listType == TeamRoleAssignStatus::Admin)
@include('teams/list')
@endif

@endsection