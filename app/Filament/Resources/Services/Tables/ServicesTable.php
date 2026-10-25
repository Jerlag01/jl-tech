<?php

namespace App\Filament\Resources\Services\Tables;

use App\Models\Service;
use App\Support\Icons;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ServicesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn (Builder $query) => $query->withCount('projects'))
            ->reorderable('sort')
            ->defaultSort('sort')
            ->columns([
                ImageColumn::make('image')
                    ->label('')
                    ->disk('public')
                    ->square(),
                TextColumn::make('title')
                    ->label(__('admin.fields.title'))
                    ->state(fn (Service $record): string => $record->tr('title'))
                    ->description(fn (Service $record): string => (string) str($record->tr('description'))->limit(90))
                    ->searchable(query: fn (Builder $query, string $search): Builder => $query->where('title', 'like', "%{$search}%"))
                    ->wrap(),
                TextColumn::make('icon')
                    ->label(__('admin.fields.icon'))
                    ->formatStateUsing(fn (?string $state): string => Icons::options()[$state] ?? '-')
                    ->badge()
                    ->color('gray')
                    ->toggleable(),
                TextColumn::make('projects_count')
                    ->label(__('admin.resources.project.plural'))
                    ->numeric(),
                IconColumn::make('show_on_home')
                    ->label(__('admin.fields.show_on_home'))
                    ->boolean()
                    ->toggleable(),
                ToggleColumn::make('active')
                    ->label(__('admin.fields.is_published'))
                    ->disabled(fn (Service $record): bool => ! auth()->user()->can('update', $record)),
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
