<?php

namespace App\Filament\Agent\Resources\Tickets\Schemas;

use App\Models\Ticket;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ViewEntry;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class TicketInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(3)
            ->components([
                Section::make(__('helpdesk.sections.thread'))
                    ->schema([
                        ViewEntry::make('thread')
                            ->hiddenLabel()
                            ->view('filament.agent.ticket-thread'),
                    ])
                    ->columnSpan(['lg' => 2]),
                Group::make([
                    Section::make(__('helpdesk.sections.ticket'))
                        ->schema([
                            TextEntry::make('status')
                                ->label(__('helpdesk.fields.status'))
                                ->badge(),
                            TextEntry::make('priority')
                                ->label(__('helpdesk.fields.priority'))
                                ->badge(),
                            TextEntry::make('department.name')
                                ->label(__('helpdesk.fields.department')),
                            TextEntry::make('helpTopic.name')
                                ->label(__('helpdesk.fields.help_topic'))
                                ->state(fn (Ticket $record): ?string => $record->helpTopic?->label())
                                ->placeholder('—'),
                            TextEntry::make('slaPlan.name')
                                ->label(__('helpdesk.fields.sla_plan'))
                                ->placeholder('—'),
                            TextEntry::make('due_at')
                                ->label(__('helpdesk.fields.due_at'))
                                ->dateTime('d/m/Y H:i')
                                ->color(fn (Ticket $record): ?string => $record->isOverdue() ? 'danger' : null)
                                ->helperText(fn (Ticket $record): ?string => $record->isOverdue() ? __('helpdesk.tabs.overdue') : null)
                                ->placeholder('—'),
                            TextEntry::make('source')
                                ->label(__('helpdesk.fields.source'))
                                ->badge()
                                ->color('gray'),
                            TextEntry::make('site_address')
                                ->label(__('helpdesk.fields.site_address'))
                                ->placeholder('—'),
                            TextEntry::make('created_at')
                                ->label(__('helpdesk.fields.created_at'))
                                ->dateTime('d/m/Y H:i'),
                            TextEntry::make('last_activity_at')
                                ->label(__('helpdesk.fields.last_activity'))
                                ->since(),
                        ]),
                    Section::make(__('helpdesk.sections.assignment'))
                        ->schema([
                            TextEntry::make('assignee.name')
                                ->label(__('helpdesk.fields.assignee'))
                                ->placeholder(__('helpdesk.fields.unassigned')),
                            TextEntry::make('team.name')
                                ->label(__('helpdesk.fields.team'))
                                ->placeholder('—'),
                        ]),
                    Section::make(__('helpdesk.sections.client'))
                        ->schema([
                            TextEntry::make('user.name')
                                ->label(__('helpdesk.fields.name')),
                            TextEntry::make('user.organization.name')
                                ->label(__('helpdesk.fields.organization'))
                                ->placeholder(fn (Ticket $record): string => $record->user->company ?? '—'),
                            TextEntry::make('user.email')
                                ->label(__('helpdesk.fields.email'))
                                ->url(fn (Ticket $record): string => "mailto:{$record->user->email}")
                                ->copyable(),
                            TextEntry::make('user.phone')
                                ->label(__('helpdesk.fields.phone'))
                                ->placeholder('—')
                                ->url(fn (?string $state): ?string => $state ? 'tel:'.preg_replace('/[^+\d]/', '', $state) : null),
                            TextEntry::make('user.locale')
                                ->label(__('helpdesk.fields.locale'))
                                ->formatStateUsing(fn (?string $state): string => config("site.locales.{$state}", '—')),
                        ]),
                ])->columnSpan(['lg' => 1]),
            ]);
    }
}
