<?php

namespace App\Models\Concerns;

/**
 * Translatable attributes are stored as JSON objects keyed by locale: {"nl": "...", "en": "..."}.
 */
trait HasTranslations
{
    /**
     * Get a translated attribute, falling back to any other filled locale.
     */
    public function tr(string $attribute, ?string $locale = null): string
    {
        return static::pickTranslation($this->getAttribute($attribute), $locale);
    }

    /**
     * Alias of tr(), used by the helpdesk models and views.
     */
    public function translate(string $attribute, ?string $locale = null): string
    {
        return $this->tr($attribute, $locale);
    }

    /**
     * Get a translated multi-line attribute as a list of non-empty lines.
     *
     * @return list<string>
     */
    public function trLines(string $attribute, ?string $locale = null): array
    {
        return static::splitLines($this->tr($attribute, $locale));
    }

    /**
     * Get a stats attribute (a list of {value, label: {locale: text}}) with labels translated.
     *
     * @return list<array{value: string, label: string}>
     */
    public function trStats(string $attribute = 'stats', ?string $locale = null): array
    {
        return collect($this->getAttribute($attribute) ?? [])
            ->filter(fn (array $stat): bool => filled($stat['value'] ?? null))
            ->map(fn (array $stat): array => [
                'value' => (string) $stat['value'],
                'label' => static::pickTranslation($stat['label'] ?? null, $locale),
            ])
            ->values()
            ->all();
    }

    /**
     * @param  array<string, string|null>|string|null  $value
     */
    public static function pickTranslation(array|string|null $value, ?string $locale = null): string
    {
        if (! is_array($value)) {
            return (string) $value;
        }

        $locale ??= app()->getLocale();

        if (filled($value[$locale] ?? null)) {
            return (string) $value[$locale];
        }

        foreach (array_keys(config('site.locales')) as $fallback) {
            if (filled($value[$fallback] ?? null)) {
                return (string) $value[$fallback];
            }
        }

        return '';
    }

    /**
     * @return list<string>
     */
    public static function splitLines(string $text): array
    {
        return array_values(array_filter(array_map('trim', preg_split('/\R/', $text))));
    }
}
