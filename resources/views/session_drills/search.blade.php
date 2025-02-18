@push('head')
<script src="{{ asset('js/search.js') }}"></script>
@endpush

{{ csrf_field() }}

<div class="btn-group">
    <button type="button" class="btn btn-danger dropdown-toggle" data-bs-toggle="dropdown"
        aria-haspopup="true" aria-expanded="false">
        Tags
    </button>
    <div class="dropdown-menu">
        <a class="dropdown-item" href="#" onclick="All()" id="all">All</a>
        <div class="dropdown-divider"></div>
        @foreach ($tags as $tag)
        <div class="dropdown-item">
            <input
                class="form-check-input"
                type="checkbox"
                name="tags[]"
                value="{{ $tag->id }}"
                id="tag{{ $tag->id }}"
                {{ in_array($tag->id,$tagIds)? "checked" : "" }}>
            <label class="form-check-label" for="tag{{ $tag->id }}">
                {{ $tag->name }}
            </label>
        </div>
        @endforeach
    </div>
</div>

<input type="text" name="name" value="{{ $search }}" />
<input type="submit" value="Search" class="btn" />