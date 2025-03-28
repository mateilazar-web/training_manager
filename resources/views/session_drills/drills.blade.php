<table class="table table-bordered">
    <tr>
        <th>Name</th>
        <th width="280px">Action</th>
    </tr>
    @foreach ($drills as $drill)
        <tr>
            <td>@include('drills/item')</td>
            <td>
                <input type="radio" name="drill" value="{{ $drill->id }}" onclick="enableButton()" />
            </td>
        </tr>
    @endforeach
</table>