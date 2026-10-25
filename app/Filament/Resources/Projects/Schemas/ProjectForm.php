<?php

namespace App\Filament\Resources\Projects\Schemas;

use App\Filament\Support\TranslatableTabs;
use App\Models\Service;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ProjectForm
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
                        TextInput::make("category.{$locale}")
                            ->label(__('admin.fields.category'))
                            ->maxLength(120),
                        TextInput::make("badge.{$locale}")
                            ->label(__('admin.fields.badge'))
                            ->maxLength(60),
                        Textarea::make("description.{$locale}")
                            ->label(__('admin.fields.description'))
                            ->rows(5),
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
                        Toggle::make('featured')
                            ->label(__('admin.fields.featured'))
                            ->helperText(__('admin.help.featured')),
                        Toggle::make('show_on_home')
                            ->label(__('admin.fields.show_on_home')),
                        Toggle::make('highlight')
                            ->label(__('admin.fields.highlight'))
                            ->helperText(__('admin.help.highlight')),
                        TranslatableTabs::image('image', 'projects'),
                        Select::make('service_id')
                            ->label(__('admin.fields.service'))
                            ->helperText(__('admin.help.service'))
                            ->options(fn (): array => Service::query()->ordered()->get()->mapWithKeys(fn (Service $service): array => [$service->id => $service->tr('title')])->all()),
                    ])
                    ->columnSpan(['lg' => 1]),
            ]);
    }
}
