@extends('layout')

@section('content')
<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="pull-left">
            <h2>{{ $team->name }}</h2>
        </div>
        <div class="pull-right">
            <a class="btn btn-primary" href="{{ route('teams.index') }}"> Back</a>
            <a class="btn btn-primary" href="{{ route('teams.edit', $team->id) }}"> Edit</a>
        </div>
    </div>
</div>

@if (isset($owner))
<p><strong>Team owner:</strong> {{ $owner->name }}</p>
@endif

@if(count($teamUsers)>0)
<strong>Users</strong>
<table class="table table-bordered mt-1">
    <tr>
        <th>Name</th>
        <th>Role</th>
        <th width="450px">Action</th>
    </tr>
    @foreach ($teamUsers as $user)
    <tr>
        <td class="align-middle">{{ $user->name }}</td>
        <td class="align-middle">{{ $user->role }}</td>
        <td class="nowrap">
            <form action="{{ route('teams.remove_user', ['team' => $team->id, 'user' => $user->id]) }}" method="POST">
                <a class="btn btn-info" href="{{ route('users.show',$user->id) }}">Show</a>
                <a class="btn btn-primary" href="{{ route('users.edit',$user->id) }}">Edit</a>
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">Delete</button>
            </form>
        </td>
    </tr>
    @endforeach

</table>
@else
<p><strong>No users</strong></p>
@endif

@if(count($pendingUsers)>0)
<strong>Pending users</strong>
<table class="table table-bordered mt-1">
    <tr>
        <th>Name</th>
        <th>Role</th>
        <th width="450px">Action</th>
    </tr>

    @foreach ($pendingUsers as $user)
    <tr>
        <td class="align-middle">{{ $user->name }}</td>
        <td class="align-middle">{{ $user->role }}</td>
        <td class="nowrap">
            <form action="{{ route('teams.remove_user', ['team' => $team->id, 'user' => $user->id]) }}" method="POST">
                @csrf

                @can('view', $user)
                <a class="btn btn-info" href="{{ route('users.show',$user->id) }}">Show</a>



                @can('view-team-member-section')

                <a class="btn btn-primary" href="{{ route('users.edit',$user->id) }}">Edit</a>


                @method('DELETE')
                <button type="submit" class="btn btn-danger">Delete</button>

                @endcan

                @endcan
            </form>
        </td>
    </tr>
    @endforeach

</table>
@else
<p><strong>No pending users</strong></p>
@endif
@endsection