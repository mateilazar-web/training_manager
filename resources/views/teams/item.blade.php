<td>
    <a data-bs-toggle="collapse" href="#collapse{{ $team->id }}" role="button" aria-expanded="false" aria-controls="collapseExample">
        {{ $team->name }}
    </a>
    <div class="collapse" id="collapse{{ $team->id }}">
        <div class="card card-body">
        </div>
    </div>
</td>
<td class="nowrap">{{ $team->user->name }}</td>