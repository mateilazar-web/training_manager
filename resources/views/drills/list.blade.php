<table class="table table-bordered">
    <tr>
        <th>Name</th>
        <th width="280px">Action</th>
    </tr>
    @foreach ($drills as $drill)
    <tr>
        <td>
            @include('drills/item')
        </td>
        <td class="nowrap">
            <form action="{{ route('drills.destroy',$drill->id) }}" method="POST">
                <a class="btn btn-info" href="{{ route('drills.show',$drill->id) }}">Show</a>
                <a class="btn btn-primary" href="{{ route('drills.edit',$drill->id) }}">Edit</a>
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">Delete</button>
            </form>
        </td>
    </tr>
    @endforeach

</table>
{{ $drills->links() }}