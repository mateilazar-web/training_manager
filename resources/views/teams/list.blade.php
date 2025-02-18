<div class="row">
    <div class="col-lg-12">
        <div class="pull-left">

            <form action="{{ url("teams.search") }}" method="GET" id="searchForm">
                @include('teams/search')
            </form>
        </div>
    </div>
</div>
<form action="{{ route('teams.request_access') }}" method="POST">
    <table class="table table-bordered">
        <tr>
            <th>Team</th>
            <th>Owner</th>
            <th width="280px">Action</th>
        </tr>
        @foreach ($teams as $team)
        <tr>
            @include('teams/item')
            <td>
                <form action="{{ route('teams.destroy',$team->id) }}" method="POST">
                    <a class="btn btn-info" href="{{ route('teams.show',$team->id) }}">Show</a>
                    <a class="btn btn-primary" href="{{ route('teams.edit',$team->id) }}">Edit</a>
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </td>
        </tr>
        @endforeach

    </table>
</form>