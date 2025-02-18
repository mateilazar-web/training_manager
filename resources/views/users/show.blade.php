@extends('layout')

@section('content')
<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="pull-left">
            <h2>{{ $user->name }}</h2>
        </div>
        <div class="pull-right">
            <a class="btn btn-primary"
                href="{{  Gate::allows('view-admin-section') 
                        ? route('users.index') 
                        : route('teams.show', $user->userTeamRoles[0]->team_id)
                    }}"> Back</a>
            <a class="btn btn-primary" href="{{ route('users.edit',$user->id) }}"> Edit</a>
        </div>
    </div>
</div>

<br />

<div class="row">

    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="form-group">
            <strong>Role:</strong>
            {{ $user->role->name }}
        </div>
    </div>

    @if(empty($teamRole))
    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="form-group">
            <strong>No team</strong>
        </div>
    </div>
    @else
    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="form-group">
            <strong>Team:</strong>
            {{ $teamRole->team->name }}
        </div>
    </div>

    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="form-group">
            <strong>Team Role:</strong>
            {{ $teamRole->role }}
        </div>
    </div>
    @endif

</div>
@endsection