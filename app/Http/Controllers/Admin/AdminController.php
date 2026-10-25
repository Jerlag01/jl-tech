<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

abstract class AdminController extends Controller
{
    /**
     * Allowed upload types for images.
     */
    protected const IMAGE_RULES = ['nullable', 'file', 'mimes:jpg,jpeg,png,webp,gif,svg', 'max:10240'];

    /**
     * Validation rules for translated inputs named like "title[nl]" and "title[en]".
     *
     * @param  list<string>  $attributes
     * @param  list<string>  $required  Attributes that need a value in at least one language
     * @return array<string, list<string>>
     */
    protected function translatedRules(array $attributes, array $required = [], int $max = 20000): array
    {
        $rules = [];
        $locales = array_keys(config('site.locales'));

        foreach ($attributes as $attribute) {
            $rules[$attribute] = ['nullable', 'array'];

            foreach ($locales as $locale) {
                $rules["{$attribute}.{$locale}"] = ['nullable', 'string', "max:{$max}"];

                if (in_array($attribute, $required, true)) {
                    $others = collect($locales)->reject(fn (string $other): bool => $other === $locale)
                        ->map(fn (string $other): string => "{$attribute}.{$other}")
                        ->implode(',');

                    $rules["{$attribute}.{$locale}"][0] = "required_without_all:{$others}";
                }
            }
        }

        return $rules;
    }

    /**
     * The submitted value of a translated input, limited to the configured locales.
     *
     * @return array<string, string>
     */
    protected function translated(Request $request, string $attribute): array
    {
        $value = [];

        foreach (array_keys(config('site.locales')) as $locale) {
            $value[$locale] = trim((string) $request->input("{$attribute}.{$locale}", ''));
        }

        return $value;
    }

    /**
     * @param  list<string>  $attributes
     * @return array<string, array<string, string>>
     */
    protected function translatedMany(Request $request, array $attributes): array
    {
        return collect($attributes)
            ->mapWithKeys(fn (string $attribute): array => [$attribute => $this->translated($request, $attribute)])
            ->all();
    }

    /**
     * Validation rules for the repeatable "stats" rows (value + translated label).
     *
     * @return array<string, list<string>>
     */
    protected function statsRules(): array
    {
        return [
            'stats' => ['nullable', 'array', 'max:6'],
            'stats.*.value' => ['nullable', 'string', 'max:60'],
            'stats.*.label' => ['nullable', 'array'],
            'stats.*.label.*' => ['nullable', 'string', 'max:120'],
        ];
    }

    /**
     * Submitted stats rows, without empty rows.
     *
     * @return list<array{value: string, label: array<string, string>}>
     */
    protected function stats(Request $request): array
    {
        return collect($request->input('stats', []))
            ->filter(fn (array $row): bool => filled($row['value'] ?? null))
            ->map(fn (array $row): array => [
                'value' => trim($row['value']),
                'label' => collect(array_keys(config('site.locales')))
                    ->mapWithKeys(fn (string $locale): array => [$locale => trim((string) ($row['label'][$locale] ?? ''))])
                    ->all(),
            ])
            ->values()
            ->all();
    }

    /**
     * Handle an image input: store a new upload, remove on request, or keep the current file.
     */
    protected function upload(Request $request, string $input, ?string $current, string $directory): ?string
    {
        $file = $request->file($input);

        if ($file instanceof UploadedFile) {
            $this->deleteFile($current);

            return $file->store("uploads/{$directory}", 'public');
        }

        if ($request->boolean("remove_{$input}")) {
            $this->deleteFile($current);

            return null;
        }

        return $current;
    }

    protected function deleteFile(?string $path): void
    {
        if (filled($path)) {
            Storage::disk('public')->delete($path);
        }
    }
}
