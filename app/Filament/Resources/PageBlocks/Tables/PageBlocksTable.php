<?php

namespace App\Filament\Resources\PageBlocks\Tables;

use App\Models\Item;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class PageBlocksTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->reorderable('sort')
            ->defaultSort('sort')
            ->columns([
                TextColumn::make('group')
                    ->label(__('admin.fields.group'))
                    ->formatStateUsing(fn (string $state): string => config("site.item_groups.{$state}.label", $state))
                    ->badge()
                    ->color('gray'),
                TextColumn::make('value')
                    ->label(__('admin.fields.value'))
                    ->fontFamily('mono')
                    ->placeholder('-'),
                TextColumn::make('title')
                    ->label(__('admin.fields.title'))
                    ->state(fn (Item $record): string => $record->tr('title'))
                    ->description(fn (Item $record): string => (string) str($record->tr('subtitle') ?: $record->tr('body'))->limit(80))
                    ->searchable(query: fn (Builder $query, string $search): Builder => $query->where('title', 'like', "%{$search}%"))
                    ->wrap(),
                ToggleColumn::make('active')
                    ->label(__('admin.fields.is_published'))
                    ->disabled(fn (Item $record): bool => ! auth()->user()->can('update', $record)),
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
