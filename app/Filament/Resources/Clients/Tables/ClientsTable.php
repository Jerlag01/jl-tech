<?php

namespace App\Filament\Resources\Clients\Tables;

use App\Models\Client;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;

class ClientsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->reorderable('sort')
            ->defaultSort('sort')
            ->columns([
                ImageColumn::make('logo')
                    ->label(__('admin.fields.logo'))
                    ->disk('public')
                    ->height(32),
                TextColumn::make('name')
                    ->label(__('admin.fields.name'))
                    ->description(fn (Client $record): ?string => $record->url)
                    ->searchable(),
                IconColumn::make('in_marquee')
                    ->label(__('admin.fields.in_marquee'))
                    ->boolean(),
                IconColumn::make('in_logo_grid')
                    ->label(__('admin.fields.in_logo_grid'))
                    ->boolean(),
                ToggleColumn::make('active')
                    ->label(__('admin.fields.is_published'))
                    ->disabled(fn (Client $record): bool => ! auth()->user()->can('update', $record)),
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
