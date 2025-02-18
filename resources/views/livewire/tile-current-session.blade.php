<link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.0.0-alpha/css/bootstrap.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="{{ asset('css/app.css') }}" rel="stylesheet" type="text/css">
<link href="{{ asset('css/order_list.css') }}" rel="stylesheet" type="text/css">

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
                        <button class="accordion-button collapsed " type="button" data-bs-toggle="collapse"
                            data-bs-target="#panelsStayOpen-collapse{{ $drill->id }}" aria-expanded="false"
                            aria-controls="panelsStayOpen-collapse{{ $drill->id }}">
                            {{ $drill->name }}
                        </button>
                    </h2>
                    <div id="panelsStayOpen-collapse{{ $drill->id }}" class="accordion-collapse collapse"
                        aria-labelledby="panelsStayOpen-heading{{ $drill->id }}">
                        <div class="accordion-body">
                            {!! $drill->description !!}
                            {!! $drill->link !!}
                            <form action="{{ route('session_drills.replace_list', $drill->session_drill_id) }}"
                                method="POST">
                                @csrf
                                @method('PUT')
                                <button type="submit" class="btn btn-primary">Replace drill</button>
                            </form>
                        </div>
                    </div>
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