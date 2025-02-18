<table class="table table-bordered">
    <tr>
        <th>Name</th>
        <th>Role</th>
        <th width="450px">Action</th>
    </tr>
    @foreach ($users as $user)
    <tr>
        <td>{{ $user->name }}</td>
        <td>{{ $user->role->name ?? $user->role_name }}</td>
        <td class="nowrap">
            <form action="{{ route('users.destroy',$user->id) }}" method="POST">
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