@extends('layout')

@section('content')
<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="pull-left">
            <h2>Edit User</h2>
        </div>
        <div class="pull-right">
            <a class="btn btn-primary" href="{{ route('users.show', $user->id) }}"> Back</a>
        </div>
    </div>
</div>

@if ($errors->any())
<div class="alert alert-danger">
    <strong>Whoops!</strong> There were some problems with your input.<br><br>
    <ul>
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<form action="{{ route('users.update', $user->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Name:</strong>
                <input
                    type="text"
                    name="name"
                    value="{{ $user->name }}"
                    class="form-control"
                    placeholder="Name"
                    {{ Auth::user()->cannot('updateField', [$user, 'name']) ? 'readonly' : '' }} />
            </div>
        </div>

        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Email:</strong>
                <input
                    type="text"
                    name="email"
                    value="{{ $user->email }}"
                    class="form-control"
                    placeholder="Email"
                    {{ Auth::user()->cannot('updateField', [$user, 'email']) ? 'readonly' : '' }} />
            </div>
        </div>

        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Role:</strong>
                <select class="form-control" single="single" name="role" id="role"
                    @if (Auth::user()->role_id != 1) disabled @endif>
                    @foreach ($roles as $key => $role)
                    <option
                        value="{{ $role->id }}"
                        @if ($role->id == $user->role_id) selected="selected" @endif>
                        {{ $role->name }}
                    </option>
                    @endforeach
                </select>
            </div>
        </div>



        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Team:</strong>
                <select
                    class="form-control"
                    single="single"
                    name="team"
                    id="team"
                    {{ Auth::user()->cannot('updateField', [$user, 'team']) ? 'readonly' : '' }}>

                    @foreach ($teams as $key => $team)
                    <option value="{{ $team->id }}">{{ $team->name }}</option>
                    @endforeach

                </select>
            </div>
        </div>

        @if (isset($user->userTeamRoles[0]))

        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Team Role:</strong>
                <select
                    class="form-control"
                    single="single"
                    name="teamRole"
                    id="teamRole"
                    {{ Auth::user()->cannot('updateField', [$user, 'team_role']) ? 'readonly' : '' }}>
                    @foreach ($teamRoles as $teamRole)
                    <option @if ( (!isset($user->userTeamRoles[0]) && $teamRole == 'Pending') ||
                        (isset($user->userTeamRoles[0]) && $teamRole == $user->userTeamRoles[0]->role)) selected="selected" @endif
                        value="{{ $teamRole }}">{{ $teamRole }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        @endif



        <div class="col-xs-12 col-sm-12 col-md-12 text-center">
            <button type="submit" class="btn btn-primary">Submit</button>
        </div>
    </div>

</form>
@endsection