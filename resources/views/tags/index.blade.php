@extends('layout')

@section('content')

<div class="row">
    <h2>Tags</h2>

    <div class="d-flex justify-content-between align-items-center">
        <div>

        </div>

        <div>
            <a class="btn btn-success" href="{{ route('tags.create') }}"> Create New Tag</a>
        </div>
    </div>
</div>
<br />
@if ($message = Session::get('success'))
<div class="alert alert-success">
    <p>{{ $message }}</p>
</div>
@endif

<table class="table table-bordered table-auto">
    <tr>
        <th>Name</th>
        <th>Action</th>
    </tr>
    @foreach ($tags as $tag)
    <tr>
        <td>{{ $tag->name }}</td>
        <td class="nowrap">
            <form action="{{ route('tags.destroy',$tag->id) }}" method="POST">
                <a class="btn btn-info" href="{{ route('tags.show',$tag->id) }}">Show</a>

                @if (auth()->user()->role == 'Super Admin')
                <a class="btn btn-primary" href="{{ route('tags.edit',$tag->id) }}">Edit</a>
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">Delete</button>
                @endif

            </form>
        </td>
    </tr>
    @endforeach

</table>
{{ $tags->links() }}


@endsection