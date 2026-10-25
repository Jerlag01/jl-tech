<?php

namespace App\Filament\Support;

use Closure;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;

class TranslatableTabs
{
    /**
     * One tab per site locale. The closure receives the locale and whether it
     * is the default locale, and returns the fields for that tab, named like
     * "title.{$locale}" so they write into the JSON translation column.
     *
     * @param  Closure(string, bool): array<Component>  $fields
     */
    public static function make(Closure $fields, ?string $label = null): Tabs
    {
        $default = config('site.default_locale');

        return Tabs::make($label ?? __('admin.fields.translations'))
            ->tabs(collect(config('site.locales'))
                ->map(fn (string $language, string $locale): Tab => Tab::make($language)
                    ->id("locale-{$locale}")
                    ->badge(strtoupper($locale))
                    ->schema($fields($locale, $locale === $default)))
                ->values()
                ->all())
            ->columnSpanFull();
    }

    /**
     * A public image upload restricted to safe raster formats.
     */
    public static function image(string $name, string $directory): FileUpload
    {
        // No SVG uploads: an SVG on the public disk could carry script.
        return FileUpload::make($name)
            ->label(__('admin.fields.image'))
            ->image()
            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
            ->maxSize(5120)
            ->imageEditor()
            ->disk('public')
            ->directory("uploads/{$directory}")
            ->visibility('public');
    }

    /**
     * A repeater for key figures: a value plus a label per language.
     */
    public static function stats(string $name = 'stats'): Repeater
    {
        return Repeater::make($name)
            ->hiddenLabel()
            ->schema([
                TextInput::make('value')
                    ->label(__('admin.fields.value'))
                    ->required()
                    ->maxLength(30),
                Grid::make(count(config('site.locales')))->schema(collect(config('site.locales'))
                    ->map(fn (string $language, string $locale) => TextInput::make("label.{$locale}")
                        ->label(__('admin.fields.label').' ('.strtoupper($locale).')')
                        ->required($locale === config('site.default_locale'))
                        ->maxLength(80))
                    ->values()
                    ->all()),
            ])
            ->addActionLabel(__('admin.actions.add_highlight'))
            ->maxItems(6)
            ->reorderable()
            ->collapsible()
            ->defaultItems(0);
    }
}
