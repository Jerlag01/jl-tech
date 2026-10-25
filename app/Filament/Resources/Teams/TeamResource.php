<?php

namespace App\Filament\Resources\Teams;

use App\Enums\AdminNavigationGroup;
use App\Filament\Resources\Teams\Pages\CreateTeam;
use App\Filament\Resources\Teams\Pages\EditTeam;
use App\Filament\Resources\Teams\Pages\ListTeams;
use App\Models\Team;
use App\Models\User;
use BackedEnum;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use UnitEnum;

class TeamResource extends Resource
{
    protected static ?string $model = Team::class;

    protected static bool $hasTitleCaseModelLabel = false;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUserGroup;

    protected static string|UnitEnum|null $navigationGroup = AdminNavigationGroup::Helpdesk;

    protected static ?int $navigationSort = 3;

    protected static ?string $recordTitleAttribute = 'name';

    public static function getModelLabel(): string
    {
        return __('helpdesk.resources.team.singular');
    }

    public static function getPluralModelLabel(): string
    {
        return __('helpdesk.resources.team.plural');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make()
                ->columns(2)
                ->columnSpanFull()
                ->schema([
                    TextInput::make('name')
                        ->label(__('helpdesk.fields.name'))
                        ->required()
                        ->maxLength(100),
                    Select::make('lead_id')
                        ->label(__('helpdesk.fields.team_lead'))
                        ->options(fn (): array => User::staff()->orderBy('name')->pluck('name', 'id')->all())
                        ->searchable(),
                    Select::make('members')
                        ->label(__('helpdesk.fields.members'))
                        ->relationship('members', 'name', fn (Builder $query) => $query->staff())
                        ->multiple()
                        ->preload()
                        ->columnSpanFull(),
                    Toggle::make('is_active')
                        ->label(__('helpdesk.fields.is_active'))
                        ->default(true),
                    Textarea::make('notes')
                        ->label(__('helpdesk.fields.internal_notes'))
                        ->rows(3)
                        ->columnSpanFull(),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('name')
            ->modifyQueryUsing(fn (Builder $query) => $query->with('lead')->withCount(['members', 'tickets' => fn (Builder $query) => $query->active()]))
            ->columns([
                TextColumn::make('name')
                    ->label(__('helpdesk.fields.name'))
                    ->searchable(),
                TextColumn::make('lead.name')
                    ->label(__('helpdesk.fields.team_lead'))
                    ->placeholder('—'),
                TextColumn::make('members_count')
                    ->label(__('helpdesk.fields.members')),
                TextColumn::make('tickets_count')
                    ->label(__('helpdesk.tabs.open')),
                ToggleColumn::make('is_active')
                    ->label(__('helpdesk.fields.is_active')),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListTeams::route('/'),
            'create' => CreateTeam::route('/create'),
            'edit' => EditTeam::route('/{record}/edit'),
        ];
    }
}
