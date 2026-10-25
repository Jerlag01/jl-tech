<?php

namespace App\Filament\Resources\Projects\Tables;

use App\Models\Project;
use App\Models\Service;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ProjectsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn (Builder $query) => $query->with('service'))
            ->reorderable('sort')
            ->defaultSort('sort')
            ->columns([
                ImageColumn::make('image')
                    ->label('')
                    ->disk('public')
                    ->square(),
                TextColumn::make('title')
                    ->label(__('admin.fields.title'))
                    ->state(fn (Project $record): string => $record->tr('title'))
                    ->description(fn (Project $record): string => $record->tr('category'))
                    ->searchable(query: fn (Builder $query, string $search): Builder => $query->where('title', 'like', "%{$search}%"))
                    ->wrap(),
                TextColumn::make('service_id')
                    ->label(__('admin.resources.service.singular'))
                    ->state(fn (Project $record): ?string => $record->service?->tr('title'))
                    ->placeholder('-')
                    ->toggleable(),
                IconColumn::make('featured')
                    ->label(__('admin.fields.featured'))
                    ->boolean(),
                IconColumn::make('show_on_home')
                    ->label(__('admin.fields.show_on_home'))
                    ->boolean()
                    ->toggleable(),
                ToggleColumn::make('active')
                    ->label(__('admin.fields.is_published'))
                    ->disabled(fn (Project $record): bool => ! auth()->user()->can('update', $record)),
            ])
            ->filters([
                SelectFilter::make('service_id')
                    ->label(__('admin.resources.service.singular'))
                    ->options(fn (): array => Service::query()->ordered()->get()->mapWithKeys(fn (Service $service): array => [$service->id => $service->tr('title')])->all()),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
