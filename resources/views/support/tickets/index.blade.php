@extends('support.layout', ['title' => __('support.nav.tickets')])

@section('portal')
    <x-support.hero :eyebrow="__('support.tickets.eyebrow')" :title="__('support.tickets.index_title', ['name' => auth()->user()->name])">
        {{ __('support.tickets.index_intro') }}
        <x-slot:actions>
            <a href="{{ support_route('tickets.create') }}" class="btn btn-primary"><x-site-icon name="plus" size="16" /> {{ __('support.nav.new_ticket') }}</a>
        </x-slot:actions>
    </x-support.hero>

    <div class="container portal-body">
        <x-support.flash />
        <x-support.two-factor-reminder />

        <dl class="portal-counts">
            @foreach (['active' => 'clock', 'waiting' => 'chat', 'closed' => 'check'] as $key => $icon)
                <div class="portal-card portal-count">
                    <span class="portal-icon-box"><x-site-icon :name="$icon" size="20" /></span>
                    <div>
                        <dt>{{ __('support.tickets.counts.'.$key) }}</dt>
                        <dd>{{ $counts[$key] }}</dd>
                    </div>
                </div>
            @endforeach
        </dl>

        <div class="portal-card">
            <div class="portal-toolbar">
                <nav class="portal-tabs" aria-label="{{ __('support.tickets.filter') }}">
                    @foreach (['active', 'closed'] as $filter)
                        <a href="{{ support_route('tickets.index', ['status' => $filter, 'search' => request('search')]) }}" @class(['active' => $status === $filter]) @if ($status === $filter) aria-current="page" @endif>{{ __('support.tickets.filters.'.$filter) }}</a>
                    @endforeach
                </nav>
                <form method="GET" action="{{ support_route('tickets.index') }}" class="portal-search" role="search">
                    <input type="hidden" name="status" value="{{ $status }}">
                    <label for="search" class="sr-only">{{ __('support.tickets.search') }}</label>
                    <x-site-icon name="search" size="16" />
                    <input id="search" name="search" type="search" value="{{ request('search') }}" placeholder="{{ __('support.tickets.search') }}" class="form-input">
                </form>
            </div>

            @if ($tickets->isEmpty())
                <div class="portal-empty">
                    <x-site-icon name="inbox" size="40" />
                    <p><strong>{{ __('support.tickets.empty_title') }}</strong></p>
                    <p>{{ __('support.tickets.empty_text') }}</p>
                    <a href="{{ support_route('tickets.create') }}" class="btn btn-primary">{{ __('support.nav.new_ticket') }}</a>
                </div>
            @else
                <ul class="portal-ticket-list">
                    @foreach ($tickets as $ticket)
                        <li>
                            <a href="{{ support_route('tickets.show', $ticket) }}">
                                <div class="portal-ticket-main">
                                    <span class="portal-mono">{{ $ticket->reference }}</span>
                                    <span class="portal-muted">{{ $ticket->helpTopic?->label() }}</span>
                                    <strong>{{ $ticket->subject }}</strong>
                                </div>
                                <div class="portal-ticket-meta">
                                    <x-support.priority :priority="$ticket->priority" />
                                    <x-support.status :status="$ticket->status" />
                                    <span title="{{ __('support.tickets.messages') }}"><x-site-icon name="chat" size="14" /> {{ $ticket->public_messages_count }}</span>
                                    <time datetime="{{ $ticket->last_activity_at?->toIso8601String() }}">{{ $ticket->last_activity_at?->diffForHumans() }}</time>
                                </div>
                            </a>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>

        @if ($tickets->hasPages())
            <nav class="news-pagination">
                @if ($tickets->previousPageUrl())<a href="{{ $tickets->previousPageUrl() }}" class="btn btn-outline">←</a>@endif
                <span>{{ $tickets->currentPage() }} / {{ $tickets->lastPage() }}</span>
                @if ($tickets->nextPageUrl())<a href="{{ $tickets->nextPageUrl() }}" class="btn btn-outline">→</a>@endif
            </nav>
        @endif
    </div>
@endsection
