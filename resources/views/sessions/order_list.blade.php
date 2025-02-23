@push('head')
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script>
    $(function() {
        $("#sortable").sortable();
        $("#sortable").disableSelection();
        $("#sortable" ).on( "sortupdate", function( event, ui ) {
            var ids = $("#sortable").sortable('toArray', {
               attribute: 'data-id'
            });

            $.post('{{ route('sessions.reorder',$session->id) }}', {drillIds:ids})
                .done(function(response,statusCode){ 
                    location.reload();
                })
                .fail(function(xhr, status, error) {
                    console.log(xhr.responseText);
                });
        });        

        $(".dropdown-item").on("click",function(){
            var action = $(this).text();
            var sessionDrillId = $(this).attr("sessionDrillId");

            if (action == "Replace"){
                var url = '{{ route('session_drills.replace_list', ":id") }}';
                url = url.replace(':id', sessionDrillId);
                window.location.href = url;
            } else {
                var url = '{{ route('session_drills.destroy', ":id") }}';
                url = url.replace(':id', sessionDrillId);
                $.ajax(url, {type : 'DELETE', data: {
                    "_token": "{{ csrf_token() }}"
                    } })
                .done(function(response,statusCode){ 
                    console.log(response);
                    location.reload();
                })
                .fail(function(xhr, status, error) {
                    console.log(xhr.responseText);
                });
            }
        });
    });
</script>
@endpush


<div class="accordion" id="accordionPanels">
    <div class="list list-row card" id="sortable" data-sortable-id="0" aria-dropeffect="move">

        @foreach ($drills as $key => $drill)
            <div class="list-item" 
               data-id="{{ $drill->id }}" 
               data-item-sortable-id="0" 
               draggable="true" 
               role="option"
               aria-grabbed="false" style="">
                <div class="flex">
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="panelsStayOpen-heading{{ $drill->id }}">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
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
                <div>
                    <div class="item-action dropdown">
                        <a href="#" data-bs-toggle="dropdown"  class="text-muted" data-abc="true">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="feather feather-more-vertical">
                                <circle cx="12" cy="12" r="1"></circle>
                                <circle cx="12" cy="5" r="1"></circle>
                                <circle cx="12" cy="19" r="1"></circle>
                            </svg>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="#" sessionDrillID="{{ $drill->session_drill_id }}">Delete</a></li>
                            <li><a class="dropdown-item" href="#" sessionDrillID="{{ $drill->session_drill_id }}">Replace</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

</div>
