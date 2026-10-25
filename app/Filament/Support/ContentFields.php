<?php

namespace App\Filament\Support;

use App\Models\Content;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\Field;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Section;
use Illuminate\Support\Facades\Storage;

/**
 * Reads, writes and builds form fields for the editable content defined in config/content.php.
 * A field key such as "home.hero_title_1" is also its form state path.
 */
class ContentFields
{
    /**
     * Form state for the given field definitions, with translated values per locale.
     *
     * @param  array<string, array<string, mixed>>  $fields
     * @return array<string, mixed>
     */
    public static function state(array $fields): array
    {
        $state = [];

        foreach ($fields as $key => $field) {
            $value = Content::raw($key);

            $value = match (true) {
                $field['type'] === 'boolean' => (bool) $value,
                $field['type'] === 'image' => filled($value) ? $value : null,
                $field['tr'] ?? false => collect(config('site.locales'))->mapWithKeys(fn (string $language, string $locale): array => [
                    $locale => is_array($value) ? ($value[$locale] ?? '') : '',
                ])->all(),
                default => is_array($value) ? '' : $value,
            };

            data_set($state, $key, $value);
        }

        return $state;
    }

    /**
     * Store the submitted values of the given fields; replaced images are deleted from disk.
     *
     * @param  array<string, array<string, mixed>>  $fields
     * @param  array<string, mixed>  $data
     */
    public static function save(array $fields, array $data): void
    {
        foreach ($fields as $key => $field) {
            $submitted = data_get($data, $key);

            if ($field['type'] === 'image') {
                $old = Content::text($key);
                $submitted = is_array($submitted) ? (array_values($submitted)[0] ?? null) : $submitted;

                if (filled($old) && $old !== $submitted) {
                    Storage::disk('public')->delete($old);
                }
            }

            Content::put($key, match (true) {
                $field['type'] === 'boolean' => $submitted ? '1' : '0',
                $field['tr'] ?? false => collect(config('site.locales'))->mapWithKeys(fn (string $language, string $locale): array => [
                    $locale => trim((string) ($submitted[$locale] ?? '')),
                ])->all(),
                default => trim((string) ($submitted ?? '')),
            });
        }
    }

    /**
     * Form components for a group of fields: the single-language fields in a section,
     * the translated ones in one tab per language.
     *
     * @param  array<string, array<string, mixed>>  $fields
     * @return array<Component|Field>
     */
    public static function components(array $fields, ?string $description = null): array
    {
        $plain = collect($fields)->reject(fn (array $field): bool => $field['tr'] ?? false);
        $translated = collect($fields)->filter(fn (array $field): bool => $field['tr'] ?? false);
        $components = [];

        if ($plain->isEmpty() && $description !== null) {
            $components[] = Section::make()->description($description)->schema([]);
        }

        if ($plain->isNotEmpty()) {
            $components[] = Section::make()
                ->description($description)
                ->columns(2)
                ->schema($plain->map(fn (array $field, string $key): Field => static::field($key, $field))->values()->all());
        }

        if ($translated->isNotEmpty()) {
            $components[] = TranslatableTabs::make(fn (string $locale): array => $translated
                ->map(fn (array $field, string $key): Field => static::field("{$key}.{$locale}", $field))
                ->values()
                ->all());
        }

        return $components;
    }

    /**
     * @param  array<string, mixed>  $field
     */
    public static function field(string $name, array $field): Field
    {
        $component = match ($field['type']) {
            'textarea' => Textarea::make($name)->rows(3)->columnSpanFull(),
            'html' => RichEditor::make($name)
                ->toolbarButtons([['bold', 'italic', 'link'], ['h2', 'h3'], ['bulletList', 'orderedList'], ['undo', 'redo']])
                ->columnSpanFull(),
            'code' => Textarea::make($name)->rows(10)->extraInputAttributes(['class' => 'font-mono', 'spellcheck' => 'false'])->columnSpanFull(),
            'image' => TranslatableTabs::image($name, 'content'),
            'boolean' => Toggle::make($name),
            'color' => ColorPicker::make($name)->regex('/^#[0-9a-fA-F]{6}$/'),
            'number' => TextInput::make($name)->numeric(),
            'email' => TextInput::make($name)->email()->maxLength(190),
            'url' => TextInput::make($name)->url()->maxLength(500),
            default => TextInput::make($name)->maxLength(500),
        };

        return $component
            ->label($field['label'])
            ->helperText($field['help'] ?? null);
    }
}
