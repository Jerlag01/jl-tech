@if ($paginator->hasPages())
    <nav class="pagination">
        @if ($paginator->onFirstPage())<span class="btn btn-small disabled">← Previous</span>@else<a href="{{ $paginator->previousPageUrl() }}" class="btn btn-small">← Previous</a>@endif
        <span class="muted">Page {{ $paginator->currentPage() }} of {{ $paginator->lastPage() }}</span>
        @if ($paginator->hasMorePages())<a href="{{ $paginator->nextPageUrl() }}" class="btn btn-small">Next →</a>@else<span class="btn btn-small disabled">Next →</span>@endif
    </nav>
@endif
