@extends('layout')

@push('head')
<script src="{{ asset('js/search.js') }}"></script>
@endpush

@section('content')

<div class="row">
    <h2>Sessions</h2>

    <div class="col-lg-12">

        <div class="d-flex justify-content-between align-items-center">
            <div>
                <form class="d-flex" action="{{ url('sessions/search') }}" method="GET">

                    {{ csrf_field() }}

                    <div class="btn-group me-1">
                        <button type="button" class="btn btn-danger dropdown-toggle" data-bs-toggle="dropdown"
                            aria-haspopup="true" aria-expanded="false">
                            Users
                        </button>

                        <div class="dropdown-menu">
                            <a class="dropdown-item" href="#" onclick="All()" id="all">All</a>
                            <div class="dropdown-divider"></div>
                            @foreach ($teamUsers as $teamUser)
                            <div class="dropdown-item">
                                <input
                                    class="form-check-input"
                                    type="checkbox"
                                    name="teamUsers[]"
                                    value="{{ $teamUser->id }}"
                                    id="teamUser{{ $teamUser->id }}"
                                    {{ in_array($teamUser->id, $userIds)? "checked" : "" }}>

                                <label class="form-check-label" for="teamUser{{ $teamUser->id }}">
                                    {{ $teamUser->id==Auth::user()->id ? "Me" : $teamUser->name }}
                                </label>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <input class="form-control d-inline-block me-1" type="text" name="name" value="{{ $search }}" />
                    <input type="submit" value="Search" class="btn btn-primary" />
                </form>
            </div>

            <div>
                <a class="btn btn-info" href="{{ route('sessions.show',$currentSessionId) }}">Current Session</a>
                <a class="btn btn-success" href="{{ route('sessions.create') }}"> Create New Session</a>
            </div>
        </div>
    </div>
</div>

@if ($message = Session::get('success'))
<div class="alert alert-success">
    <p>{{ $message }}</p>
</div>
@endif
<br />
@if (count($sessions) == 0)
<div>
    No sessions
</div>
@else
<table class="table table-bordered">
    <tr>
        <th>Name</th>
        <th>Tag</th>
        <th>Action</th>
    </tr>
    @foreach ($sessions as $session)
    <tr>
        <td class="align-middle">{{ $session->name }}</td>
        <td class="align-middle">{{ $session->tag->name }}</td>
        <td class="nowrap">


            <div class="d-flex align-items-center">

                <a class="btn btn-info  me-1" href="{{ route('sessions.show',$session->id) }}">Show</a>

                <a class="btn btn-primary  me-1" href="{{ route('sessions.edit',$session->id) }}">Edit</a>

                <form action="{{ route('sessions.destroy',$session->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger me-1">Delete</button>
                </form>

                <form action="{{ route('sessions.duplicate',$session->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <button type="submit" class="btn btn-info">Duplicate</button>
                </form>
            </div>

        </td>
    </tr>
    @endforeach

</table>
{{ $sessions->links() }}
@endif



@endsection