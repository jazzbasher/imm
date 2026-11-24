@extends('adminlte::page')

@section('title', 'POD Search') 

@section('content_header')


@include('partials.flash-messages')
    
@stop

@section('content')

<div class="container py-4">
    <h2>POD Search</h2>

    <form method="GET" action="{{ route('pod.search') }}" class="mb-4">
        <div class="input-group">
            <input type="text" name="q" value="{{ $query }}" class="form-control" autocomplete="off"
                   placeholder="Search Order Number, Pick Ticket Number, Ship To..." onfocus="this.setSelectionRange(this.value.length, this.value.length);" required autofocus>
            <button type="submit" class="btn btn-primary">Search</button>
        </div>
    </form>

    @if($query !== '')
        <p class="text-muted">Found {{ $results->total() }} matching document(s) for "<strong>{{ $query }}</strong>"</p>

        <div class="list-group mb-3">
            @forelse($results as $doc)
                <a href="{{ route('pod.view', $doc) }}" target="_blank" class="list-group-item list-group-item-action">
                    <div class="d-flex w-100 justify-content-between align-items-center">
                        <h5 class="mb-1">{{ $doc->filename }}</h5>
                        <small class="text-muted text-nowrap ms-2">
                            {{ $doc->created_at?->format('M j, Y') }} &bull; Open PDF ↗
                        </small>
                    </div>
                    <p class="mb-1 text-muted small">
                        {{-- {{ \Illuminate\Support\Str::limit($doc->content, 220) }} --}}
                        {!! $doc->getSnippet($query) !!}
                    </p>
                </a>
            @empty
                <div class="alert alert-warning">No documents matched your search.</div>
            @endforelse
        </div>

        {{ $results->links('pagination::bootstrap-4') }}
    @endif
</div>
@endsection