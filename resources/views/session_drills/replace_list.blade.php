@extends('layout')

@push('head')
<script src="{{ asset('js/replace_list.js') }}"></script>
@endpush


@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="pull-left">
                
                <form action="{{ url("session_drills/replace_list/{$sessionDrill->id}") }}" method="GET" id="searchForm">
                    @include('session_drills/search')
                </form>                
            </div>
            <div class="pull-right">
                <a class="btn btn-success" href="{{ route('drills.create') }}"> Create New Drill</a>
            </div>
        </div>
    </div>

    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
    @endif
    <br />
    <div class="accordion" id="accordionPanels">
        <div class="accordion-item">
            <h2 class="accordion-header" id="panelsStayOpen-heading{{ $drill->id }}">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                    data-bs-target="#panelsStayOpen-collapse{{ $drill->id }}" aria-expanded="false"
                    aria-controls="panelsStayOpen-collapse{{ $drill->id }}">
                    Current drill: {{ $drill->name }}
                </button>
            </h2>
            <div id="panelsStayOpen-collapse{{ $drill->id }}" class="accordion-collapse collapse"
                aria-labelledby="panelsStayOpen-heading{{ $drill->id }}">
                <div class="accordion-body">
                    {!! $drill->description !!}
                    {!! $drill->link !!}
                </div>
            </div>
        </div>
    </div>

    <form action="{{ route('session_drills.replace', $sessionDrill->id) }}" method="POST">
        @csrf
       
        @include('session_drills/drills')
        
        <div class="pull-right">
            <button type="submit" class="btn btn-primary" id="replaceDrillBtn" disabled>Replace drill</button>
            <a class="btn btn-info" href="{{ route('sessions.show',$sessionDrill->session_id) }}">Back</a>
        </div>
    </form>
    {{ $drills->links() }}
@endsection
