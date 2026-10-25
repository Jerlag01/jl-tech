<?php

namespace App\Filament\Resources\Clients\Schemas;

use App\Filament\Support\TranslatableTabs;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ClientForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(3)
            ->components([
                Section::make()
                    ->columns(2)
                    ->schema([
                        TextInput::make('name')
                            ->label(__('admin.fields.name'))
                            ->helperText(__('admin.help.client_name'))
                            ->required()
                            ->maxLength(120),
                        TextInput::make('url')
                            ->label(__('admin.fields.website'))
                            ->url()
                            ->rule('url:http,https')
                            ->maxLength(500)
                            ->placeholder('https://'),
                        TranslatableTabs::image('logo', 'clients')
                            ->label(__('admin.fields.logo'))
                            ->helperText(__('admin.help.logo'))
                            ->imageEditor(false)
                            ->columnSpanFull(),
                    ])
                    ->columnSpan(['lg' => 2]),

                Section::make(__('admin.sections.display'))
                    ->schema([
                        Toggle::make('active')
                            ->label(__('admin.fields.is_published'))
                            ->default(true),
                        Toggle::make('in_marquee')
                            ->label(__('admin.fields.in_marquee'))
                            ->default(true),
                        Toggle::make('in_logo_grid')
                            ->label(__('admin.fields.in_logo_grid'))
                            ->default(true),
                    ])
                    ->columnSpan(['lg' => 1]),
            ]);
    }
}
