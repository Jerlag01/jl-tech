<?php

namespace App\Filament\Resources\JobOffers\Schemas;

use App\Filament\Support\TranslatableTabs;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class JobOfferForm
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
                            ->maxLength(255),
                        TextInput::make("badge.{$locale}")
                            ->label(__('admin.fields.category'))
                            ->maxLength(60),
                        Textarea::make("description.{$locale}")
                            ->label(__('admin.fields.description'))
                            ->rows(6),
                        Textarea::make("tags.{$locale}")
                            ->label(__('admin.fields.tags'))
                            ->helperText(__('admin.help.tags'))
                            ->rows(3),
                    ]),
                ])->columnSpan(['lg' => 2]),

                Section::make(__('admin.sections.publishing'))
                    ->schema([
                        Toggle::make('active')
                            ->label(__('admin.fields.is_published'))
                            ->default(true),
                    ])
                    ->columnSpan(['lg' => 1]),
            ]);
    }
}
