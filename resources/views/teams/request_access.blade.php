<div class="row">
    <div class="col-lg-12">
        <div class="pull-left">

            <form action="{{ url("teams.search") }}" method="GET" id="searchForm">
                @include('teams/search')
            </form>
        </div>
    </div>
</div>

<form action="{{ route('teams.process_access_request') }}" method="POST">
    @csrf
    <table class="table table-bordered">
        <tr>
            <th>Team</th>
            <th>Owner</th>
            <th width="280px">Action</th>
        </tr>
        @foreach ($teams as $team)
        <tr>
            @include('teams/item')
            <td class="text-center align-middle">
                <input type="radio" name="team" value="{{ $team->id }}" />
            </td>
        </tr>
        @endforeach

    </table>
    <button type="submit" class="btn btn-primary">Request access</button>
</form>