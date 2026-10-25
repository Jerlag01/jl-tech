<?php

namespace App\Filament\Resources\Services\Schemas;

use App\Filament\Support\TranslatableTabs;
use App\Support\Icons;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class ServiceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(3)
            ->components([
                Group::make([
                    TranslatableTabs::make(fn (string $locale, bool $isDefault): array => [
                        TextInput::make("title.{$locale}")
                            ->label(__('admin.fields.title'))
                            ->required($isDefault)
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(function (?string $state, Set $set, string $operation) use ($isDefault): void {
                                if ($isDefault && $operation === 'create') {
                                    $set('slug', Str::slug((string) $state));
                                }
                            }),
                        TextInput::make("subtitle.{$locale}")
                            ->label(__('admin.fields.subtitle'))
                            ->maxLength(255),
                        Textarea::make("description.{$locale}")
                            ->label(__('admin.fields.description'))
                            ->rows(4),
                        Textarea::make("features.{$locale}")
                            ->label(__('admin.fields.features'))
                            ->helperText(__('admin.help.features'))
                            ->rows(6),
                    ]),

                    Section::make(__('admin.sections.highlights'))
                        ->description(__('admin.help.highlights'))
                        ->schema([TranslatableTabs::stats()]),
                ])->columnSpan(['lg' => 2]),

                Section::make(__('admin.sections.details'))
                    ->schema([
                        Toggle::make('active')
                            ->label(__('admin.fields.is_published'))
                            ->default(true),
                        Toggle::make('show_on_home')
                            ->label(__('admin.fields.show_on_home'))
                            ->default(true),
                        TranslatableTabs::image('image', 'services'),
                        Select::make('icon')
                            ->label(__('admin.fields.icon'))
                            ->options(Icons::options())
                            ->searchable()
                            ->default('bolt'),
                        TextInput::make('slug')
                            ->label(__('admin.fields.anchor'))
                            ->helperText(__('admin.help.anchor'))
                            ->required()
                            ->alphaDash()
                            ->maxLength(120)
                            ->unique(ignoreRecord: true),
                    ])
                    ->columnSpan(['lg' => 1]),
            ]);
    }
}
