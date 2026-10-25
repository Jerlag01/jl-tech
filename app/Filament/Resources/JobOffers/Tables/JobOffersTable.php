<?php

namespace App\Filament\Resources\JobOffers\Tables;

use App\Models\JobOffer;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class JobOffersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->reorderable('sort')
            ->defaultSort('sort')
            ->columns([
                TextColumn::make('title')
                    ->label(__('admin.fields.title'))
                    ->state(fn (JobOffer $record): string => $record->tr('title'))
                    ->description(fn (JobOffer $record): string => implode(' · ', $record->trLines('tags')))
                    ->searchable(query: fn (Builder $query, string $search): Builder => $query->where('title', 'like', "%{$search}%"))
                    ->wrap(),
                TextColumn::make('badge')
                    ->label(__('admin.fields.category'))
                    ->state(fn (JobOffer $record): string => $record->tr('badge'))
                    ->badge()
                    ->color('gray')
                    ->placeholder('-'),
                ToggleColumn::make('active')
                    ->label(__('admin.fields.is_published'))
                    ->disabled(fn (JobOffer $record): bool => ! auth()->user()->can('update', $record)),
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
