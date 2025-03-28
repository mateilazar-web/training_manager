<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="{{ asset('css/app.css') }}" rel="stylesheet" type="text/css">


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>


<x-dashboard-tile :position="$position">
    @if ($session !== null)
    <h3>Current session</h3>
    <br />
    <div class="accordion" id="accordionPanels">

        @foreach ($drills as $key => $drill)
        <div class="list-item"
            data-id="{{ $drill->id }}"
            role="option"
            style="">
            <div class="flex d-grid gap-2">
                <div class="accordion-item ">
                    <h2 class="accordion-header" id="panelsStayOpen-heading{{ $drill->id }}">
                        <button class="accordion-button collapsed no-arrow" type="button">
                            {{ $drill->name }}
                        </button>
                    </h2>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    <br />
    <a target="_parent" href="{{ route('sessions.show',$session->id) }}">See more</a>
    @else
    <p><strong>No session.</strong></p>
    <a class="btn btn-success" href={{ route('sessions.create') }}> Create New Session</a>
    @endif
</x-dashboard-tile>