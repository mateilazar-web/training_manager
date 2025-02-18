@push('head')
<script src="{{ asset('js/search.js') }}"></script>
@endpush

{{ csrf_field() }}

<input type="text" name="name" value="{{ $search }}" />
<input type="submit" value="Search" class="btn" />