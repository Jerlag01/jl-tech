<?php

namespace App\Filament\Resources\PageBlocks\Schemas;

use App\Filament\Support\TranslatableTabs;
use App\Support\Icons;
use Closure;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;

/**
 * The fields shown depend on the chosen section (config site.item_groups).
 */
class PageBlockForm
{
    public static function configure(Schema $schema): Schema
    {
        $uses = fn (string $field): Closure => fn (Get $get): bool => isset(config('site.item_groups.'.$get('group').'.fields')[$field]);
        $label = fn (string $field): Closure => fn (Get $get): string => config('site.item_groups.'.$get('group').'.fields.'.$field) ?? __('admin.fields.'.$field);

        return $schema
            ->columns(3)
            ->components([
                Group::make([
                    Section::make()
                        ->schema([
                            Select::make('group')
                                ->label(__('admin.fields.group'))
                                ->options(collect(config('site.item_groups'))->map(fn (array $group): string => $group['label'])->all())
                                ->required()
                                ->live(),
                            TextInput::make('value')
                                ->label($label('value'))
                                ->maxLength(60)
                                ->visible($uses('value')),
                        ]),

                    TranslatableTabs::make(fn (string $locale, bool $isDefault): array => [
                        TextInput::make("title.{$locale}")
                            ->label($label('title'))
                            ->required($isDefault)
                            ->maxLength(255),
                        TextInput::make("subtitle.{$locale}")
                            ->label($label('subtitle'))
                            ->maxLength(255)
                            ->visible($uses('subtitle')),
                        Textarea::make("body.{$locale}")
                            ->label($label('body'))
                            ->rows(4)
                            ->visible($uses('body')),
                        Textarea::make("list.{$locale}")
                            ->label($label('list'))
                            ->helperText(__('admin.help.list'))
                            ->rows(5)
                            ->visible($uses('list')),
                    ]),
                ])->columnSpan(['lg' => 2]),

                Section::make(__('admin.sections.details'))
                    ->schema([
                        Toggle::make('active')
                            ->label(__('admin.fields.is_published'))
                            ->default(true),
                        TranslatableTabs::image('image', 'blocks')
                            ->label($label('image'))
                            ->visible($uses('image')),
                        Select::make('icon')
                            ->label(__('admin.fields.icon'))
                            ->options(Icons::options())
                            ->searchable()
                            ->visible($uses('icon')),
                    ])
                    ->columnSpan(['lg' => 1]),
            ]);
    }
}
