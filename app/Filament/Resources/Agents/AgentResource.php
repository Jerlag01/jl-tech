<?php

namespace App\Filament\Resources\Agents;

use App\Enums\AdminNavigationGroup;
use App\Enums\UserRole;
use App\Filament\Resources\Agents\Pages\CreateAgent;
use App\Filament\Resources\Agents\Pages\EditAgent;
use App\Filament\Resources\Agents\Pages\ListAgents;
use App\Filament\Support\ResetTwoFactorAction;
use App\Models\User;
use BackedEnum;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Pages\CreateRecord;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rules\Password;
use UnitEnum;

/**
 * Agent and admin accounts with their department access and teams.
 */
class AgentResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $slug = 'agents';

    protected static bool $hasTitleCaseModelLabel = false;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedIdentification;

    protected static string|UnitEnum|null $navigationGroup = AdminNavigationGroup::Helpdesk;

    protected static ?int $navigationSort = 1;

    protected static ?string $recordTitleAttribute = 'name';

    public static function getModelLabel(): string
    {
        return __('helpdesk.resources.agent.singular');
    }

    public static function getPluralModelLabel(): string
    {
        return __('helpdesk.resources.agent.plural');
    }

    /**
     * Agents are users, but managed with the "helpdesk" permission rather than "users".
     */
    public static function canViewAny(): bool
    {
        return auth()->user()?->hasPermission('helpdesk.view') ?? false;
    }

    public static function canCreate(): bool
    {
        return auth()->user()?->hasPermission('helpdesk.create') ?? false;
    }

    public static function canEdit(Model $record): bool
    {
        return auth()->user()?->hasPermission('helpdesk.update') ?? false;
    }

    public static function canDelete(Model $record): bool
    {
        return (auth()->user()?->hasPermission('helpdesk.delete') ?? false) && $record->isNot(auth()->user());
    }

    public static function canDeleteAny(): bool
    {
        return auth()->user()?->hasPermission('helpdesk.delete') ?? false;
    }

    /**
     * @return Builder<User>
     */
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->whereIn('support_role', [UserRole::Agent, UserRole::Admin]);
    }

    public static function form(Schema $schema): Schema
    {
        $isSelf = fn (?User $record): bool => $record?->is(auth()->user()) ?? false;

        return $schema->components([
            Section::make(__('helpdesk.sections.account'))
                ->columns(2)
                ->columnSpanFull()
                ->schema([
                    TextInput::make('name')
                        ->label(__('helpdesk.fields.name'))
                        ->required()
                        ->maxLength(100),
                    TextInput::make('email')
                        ->label(__('helpdesk.fields.email'))
                        ->email()
                        ->required()
                        ->maxLength(255)
                        ->unique(ignoreRecord: true),
                    TextInput::make('phone')
                        ->label(__('helpdesk.fields.phone'))
                        ->maxLength(50),
                    Select::make('locale')
                        ->label(__('helpdesk.fields.locale'))
                        ->options(config('site.locales'))
                        ->default(config('site.default_locale'))
                        ->required(),
                    TextInput::make('password')
                        ->label(__('helpdesk.fields.password'))
                        ->password()
                        ->revealable()
                        ->rule(Password::defaults())
                        ->required(fn ($livewire): bool => $livewire instanceof CreateRecord)
                        ->dehydrated(fn (?string $state): bool => filled($state))
                        ->helperText(fn ($livewire): ?string => $livewire instanceof CreateRecord ? null : __('helpdesk.help.password')),
                    Toggle::make('is_active')
                        ->label(__('helpdesk.fields.is_active'))
                        ->helperText(__('helpdesk.help.is_active'))
                        ->default(true)
                        ->inline(false)
                        // Admins can't lock themselves out.
                        ->disabled($isSelf)
                        ->dehydrated(fn (?User $record): bool => ! $isSelf($record)),
                ]),
            Section::make(__('helpdesk.sections.access'))
                ->columns(2)
                ->columnSpanFull()
                ->schema([
                    Select::make('support_role')
                        ->label(__('helpdesk.fields.role'))
                        ->options([
                            UserRole::Agent->value => UserRole::Agent->getLabel(),
                            UserRole::Admin->value => UserRole::Admin->getLabel(),
                        ])
                        ->helperText(__('helpdesk.help.role'))
                        ->default(UserRole::Agent->value)
                        ->required()
                        ->disabled($isSelf)
                        ->dehydrated(fn (?User $record): bool => ! $isSelf($record)),
                    Select::make('departments')
                        ->label(__('helpdesk.fields.departments'))
                        ->helperText(__('helpdesk.help.agent_departments'))
                        ->relationship('departments', 'name')
                        ->multiple()
                        ->preload(),
                    Select::make('teams')
                        ->label(__('helpdesk.resources.team.plural'))
                        ->relationship('teams', 'name')
                        ->multiple()
                        ->preload(),
                    Textarea::make('signature')
                        ->label(__('helpdesk.fields.signature'))
                        ->helperText(__('helpdesk.help.signature'))
                        ->rows(3)
                        ->columnSpanFull(),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('name')
            ->modifyQueryUsing(fn (Builder $query) => $query->with(['departments', 'teams'])->withCount(['assignedTickets' => fn (Builder $query) => $query->active()]))
            ->columns([
                TextColumn::make('name')
                    ->label(__('helpdesk.fields.name'))
                    ->description(fn (User $record): string => $record->email)
                    ->searchable(['name', 'email'])
                    ->sortable(),
                TextColumn::make('support_role')
                    ->label(__('helpdesk.fields.role'))
                    ->badge(),
                TextColumn::make('departments.name')
                    ->label(__('helpdesk.fields.departments'))
                    ->badge()
                    ->color('gray')
                    ->placeholder(__('helpdesk.fields.all_departments')),
                TextColumn::make('teams.name')
                    ->label(__('helpdesk.resources.team.plural'))
                    ->badge()
                    ->color('gray')
                    ->placeholder('—')
                    ->toggleable(),
                TextColumn::make('assigned_tickets_count')
                    ->label(__('helpdesk.tabs.mine')),
                IconColumn::make('is_active')
                    ->label(__('helpdesk.fields.is_active'))
                    ->boolean(),
            ])
            ->filters([
                SelectFilter::make('support_role')
                    ->label(__('helpdesk.fields.role'))
                    ->options([
                        UserRole::Agent->value => UserRole::Agent->getLabel(),
                        UserRole::Admin->value => UserRole::Admin->getLabel(),
                    ]),
            ])
            ->recordActions([
                EditAction::make(),
                ResetTwoFactorAction::make(),
                DeleteAction::make()
                    ->hidden(fn (User $record): bool => $record->is(auth()->user())),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListAgents::route('/'),
            'create' => CreateAgent::route('/create'),
            'edit' => EditAgent::route('/{record}/edit'),
        ];
    }
}
