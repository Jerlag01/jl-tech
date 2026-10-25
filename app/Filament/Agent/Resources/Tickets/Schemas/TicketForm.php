<?php

namespace App\Filament\Agent\Resources\Tickets\Schemas;

use App\Enums\TicketPriority;
use App\Enums\TicketSource;
use App\Enums\UserRole;
use App\Models\Department;
use App\Models\HelpTopic;
use App\Models\Organization;
use App\Models\SlaPlan;
use App\Models\Team;
use App\Models\User;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;

class TicketForm
{
    /**
     * Edit form: ticket properties. Messages, assignment and status go through the view page actions.
     */
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()
                    ->columns(2)
                    ->columnSpanFull()
                    ->schema([
                        TextInput::make('subject')
                            ->label(__('helpdesk.fields.subject'))
                            ->required()
                            ->maxLength(150)
                            ->columnSpanFull(),
                        self::helpTopicField(),
                        self::departmentField(),
                        Select::make('sla_plan_id')
                            ->label(__('helpdesk.fields.sla_plan'))
                            ->options(fn (): array => SlaPlan::where('is_active', true)->pluck('name', 'id')->all())
                            ->helperText(__('helpdesk.help.sla_recalculates')),
                        Select::make('priority')
                            ->label(__('helpdesk.fields.priority'))
                            ->options(TicketPriority::class)
                            ->required(),
                        Select::make('source')
                            ->label(__('helpdesk.fields.source'))
                            ->options(TicketSource::class)
                            ->required(),
                        TextInput::make('site_address')
                            ->label(__('helpdesk.fields.site_address'))
                            ->maxLength(255),
                    ]),
            ]);
    }

    /**
     * Create form: open a ticket on behalf of a client, e.g. after a phone call.
     */
    public static function configureForCreate(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('helpdesk.sections.client'))
                    ->description(__('helpdesk.help.created_for_client'))
                    ->columnSpanFull()
                    ->schema([
                        Select::make('user_id')
                            ->label(__('helpdesk.fields.client'))
                            ->relationship('user', 'name', fn ($query) => $query->where('support_role', UserRole::Customer)->where('is_active', true))
                            ->getOptionLabelFromRecordUsing(fn (User $record): string => "{$record->displayName()} · {$record->email}")
                            ->searchable(['name', 'company', 'email'])
                            ->required()
                            ->createOptionForm([
                                TextInput::make('name')->label(__('helpdesk.fields.name'))->required()->maxLength(100),
                                TextInput::make('email')->label(__('helpdesk.fields.email'))->email()->required()->unique('users', 'email'),
                                TextInput::make('phone')->label(__('helpdesk.fields.phone'))->maxLength(50),
                                Select::make('organization_id')
                                    ->label(__('helpdesk.fields.organization'))
                                    ->options(fn (): array => Organization::orderBy('name')->pluck('name', 'id')->all())
                                    ->searchable(),
                            ])
                            ->createOptionUsing(fn (array $data): int => User::create([
                                ...$data,
                                'support_role' => UserRole::Customer,
                                'locale' => config('site.default_locale'),
                                // Clients created by an agent set their own password through "forgot password".
                                'password' => str()->random(40),
                            ])->getKey()),
                    ]),
                Section::make(__('helpdesk.sections.ticket'))
                    ->columns(2)
                    ->columnSpanFull()
                    ->schema([
                        Select::make('source')
                            ->label(__('helpdesk.fields.source'))
                            ->options(TicketSource::class)
                            ->default(TicketSource::Phone)
                            ->required(),
                        self::helpTopicField()
                            ->live()
                            ->afterStateUpdated(function (?string $state, Set $set): void {
                                $topic = HelpTopic::find($state);
                                if ($topic) {
                                    $set('department_id', $topic->department_id);
                                    $set('priority', $topic->default_priority);
                                }
                            }),
                        self::departmentField()
                            ->helperText(__('helpdesk.help.department_from_topic')),
                        Select::make('priority')
                            ->label(__('helpdesk.fields.priority'))
                            ->options(TicketPriority::class)
                            ->default(TicketPriority::Normal)
                            ->required(),
                        TextInput::make('subject')
                            ->label(__('helpdesk.fields.subject'))
                            ->required()
                            ->maxLength(150)
                            ->columnSpanFull(),
                        TextInput::make('site_address')
                            ->label(__('helpdesk.fields.site_address'))
                            ->maxLength(255)
                            ->columnSpanFull(),
                        Textarea::make('message')
                            ->label(__('helpdesk.fields.message'))
                            ->required()
                            ->maxLength(10000)
                            ->rows(8)
                            ->columnSpanFull(),
                    ]),
                Section::make(__('helpdesk.sections.assignment'))
                    ->columns(2)
                    ->columnSpanFull()
                    ->schema([
                        Select::make('assigned_to')
                            ->label(__('helpdesk.fields.assignee'))
                            ->options(fn (): array => User::staff()->orderBy('name')->pluck('name', 'id')->all())
                            ->searchable(),
                        Select::make('team_id')
                            ->label(__('helpdesk.fields.team'))
                            ->options(fn (): array => Team::where('is_active', true)->orderBy('name')->pluck('name', 'id')->all()),
                    ]),
            ]);
    }

    private static function helpTopicField(): Select
    {
        return Select::make('help_topic_id')
            ->label(__('helpdesk.fields.help_topic'))
            ->options(fn (): array => HelpTopic::where('is_active', true)->orderBy('sort_order')->get()
                ->mapWithKeys(fn (HelpTopic $topic): array => [$topic->id => $topic->label()])->all())
            ->searchable();
    }

    private static function departmentField(): Select
    {
        return Select::make('department_id')
            ->label(__('helpdesk.fields.department'))
            ->options(fn (): array => Department::orderBy('name')->pluck('name', 'id')->all())
            ->required();
    }
}
