{{-- Params: $action (url), $label --}}
<form method="POST" action="{{ $action }}" data-confirm="Delete this item? This cannot be undone." class="inline">
    @csrf
    @method('DELETE')
    <button type="submit" class="btn btn-small btn-danger">{{ $label ?? 'Delete' }}</button>
</form>
