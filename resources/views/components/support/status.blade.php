@props(['status'])
<span class="portal-badge portal-badge--{{ $status->value }}"><span class="portal-dot"></span>{{ $status->getLabel() }}</span>
