@extends('support.layout', ['title' => $ticket->reference])

@section('portal')
    <x-support.hero :eyebrow="$ticket->reference" :title="$ticket->subject">
        <span class="portal-hero-meta">
            <x-support.status :status="$ticket->status" />
            <x-support.priority :priority="$ticket->priority" />
            <span>{{ $ticket->helpTopic?->label() }}</span>
        </span>
        <x-slot:actions>
            <a href="{{ support_route('tickets.index') }}" class="btn btn-outline-light"><x-site-icon name="arrow-left" size="16" /> {{ __('support.tickets.back') }}</a>
        </x-slot:actions>
    </x-support.hero>

    <div class="container portal-body portal-columns">
        <div class="portal-stack">
            <x-support.flash />

            <ol class="portal-thread" aria-label="{{ __('support.tickets.conversation') }}">
                @foreach ($ticket->publicMessages as $message)
                    @php($fromStaff = $message->isFromStaff())
                    <li @class(['portal-card', 'portal-message', 'portal-message--staff' => $fromStaff])>
                        <div class="portal-message-head">
                            <span class="portal-message-author">
                                <span class="portal-avatar">{{ mb_strtoupper(mb_substr($message->author?->name ?? '?', 0, 1)) }}</span>
                                <strong>{{ $message->author?->name ?? __('support.tickets.deleted_user') }}</strong>
                                @if ($fromStaff)
                                    <span class="portal-badge portal-badge--staff">{{ helpdesk()->companyName() }}</span>
                                @endif
                            </span>
                            <time datetime="{{ $message->created_at->toIso8601String() }}" class="portal-mono">{{ $message->created_at->format('d/m/Y H:i') }}</time>
                        </div>
                        <div class="portal-message-body">{{ $message->body }}</div>
                        @if ($message->attachments->isNotEmpty())
                            <ul class="portal-message-files">
                                @foreach ($message->attachments as $attachment)
                                    <li><a href="{{ route('support.attachments.show', $attachment) }}"><x-site-icon name="paperclip" size="16" /> <span class="portal-truncate">{{ $attachment->original_name }}</span> <span class="portal-mono">{{ $attachment->humanSize() }}</span></a></li>
                                @endforeach
                            </ul>
                        @endif
                    </li>
                @endforeach
            </ol>

            @can('reply', $ticket)
                <form id="reply" method="POST" action="{{ support_route('tickets.messages.store', $ticket) }}" enctype="multipart/form-data" class="portal-card portal-form" data-submit-once>
                    @csrf
                    <h2 class="portal-card-title">{{ __('support.tickets.reply_title') }}</h2>
                    @if ($ticket->isClosed())
                        <p class="portal-note">{{ __('support.tickets.reply_reopens') }}</p>
                    @endif
                    <x-support.field name="message" type="textarea" :label="__('support.fields.message')" required maxlength="10000" rows="5" />
                    <x-support.attachments />
                    <div class="portal-form-footer portal-form-footer--end">
                        <button type="submit" class="btn btn-primary">{{ __('support.tickets.send') }} <x-site-icon name="arrow" size="16" /></button>
                    </div>
                </form>
            @else
                <div class="portal-card portal-padded portal-closed">
                    <p>{{ __('support.tickets.closed_notice') }}</p>
                    @can('reopen', $ticket)
                        <form method="POST" action="{{ support_route('tickets.reopen', $ticket) }}">
                            @csrf
                            <button type="submit" class="btn btn-outline">{{ __('support.tickets.reopen') }}</button>
                        </form>
                    @endcan
                </div>
            @endcan
        </div>

        <aside class="portal-aside">
            <div class="portal-card portal-padded">
                <span class="eyebrow">{{ __('support.tickets.details') }}</span>
                <dl class="portal-details">
                    <div><dt>{{ __('support.fields.reference') }}</dt><dd class="portal-mono">{{ $ticket->reference }}</dd></div>
                    <div><dt>{{ __('support.fields.status') }}</dt><dd><x-support.status :status="$ticket->status" /></dd></div>
                    @if ($ticket->helpTopic)
                        <div><dt>{{ __('support.fields.help_topic_id') }}</dt><dd>{{ $ticket->helpTopic->label() }}</dd></div>
                    @endif
                    @if ($ticket->department?->is_public)
                        <div><dt>{{ __('support.fields.department') }}</dt><dd>{{ $ticket->department->name }}</dd></div>
                    @endif
                    @if ($ticket->site_address)
                        <div><dt>{{ __('support.fields.site_address') }}</dt><dd>{{ $ticket->site_address }}</dd></div>
                    @endif
                    <div><dt>{{ __('support.fields.assignee') }}</dt><dd>{{ $ticket->assignee?->name ?? __('support.tickets.unassigned') }}</dd></div>
                    <div><dt>{{ __('support.fields.created_at') }}</dt><dd class="portal-mono">{{ $ticket->created_at->format('d/m/Y H:i') }}</dd></div>
                    @if ($ticket->closed_at)
                        <div><dt>{{ __('support.fields.closed_at') }}</dt><dd class="portal-mono">{{ $ticket->closed_at->format('d/m/Y H:i') }}</dd></div>
                    @endif
                </dl>
            </div>

            @can('close', $ticket)
                <form method="POST" action="{{ support_route('tickets.close', $ticket) }}" class="portal-card portal-padded">
                    @csrf
                    <p class="portal-muted">{{ __('support.tickets.close_hint') }}</p>
                    <button type="submit" class="btn btn-outline portal-full">{{ __('support.tickets.close') }}</button>
                </form>
            @endcan
        </aside>
    </div>
@endsection
