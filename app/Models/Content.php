<?php

namespace App\Models;

use App\Models\Concerns\HasTranslations;
use Database\Factories\ContentFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

/**
 * A stored value for one of the fields defined in config/content.php.
 */
#[Fillable(['key', 'value'])]
class Content extends Model
{
    /** @use HasFactory<ContentFactory> */
    use HasFactory;

    public const CACHE_KEY = 'site.contents';

    /** @var array<string, mixed>|null */
    protected static ?array $loaded = null;

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'value' => 'json',
        ];
    }

    protected static function booted(): void
    {
        static::saved(fn () => static::flush());
        static::deleted(fn () => static::flush());
    }

    public static function flush(): void
    {
        static::$loaded = null;
        Cache::forget(self::CACHE_KEY);
    }

    /**
     * All field definitions, keyed by field key, each with its group name added.
     *
     * @return array<string, array{label: string, type: string, tr?: bool, default?: mixed, help?: string, group: string}>
     */
    public static function definitions(): array
    {
        $definitions = [];

        foreach (config('content') as $group => $config) {
            foreach ($config['fields'] as $key => $field) {
                $definitions[$key] = $field + ['group' => $group];
            }
        }

        return $definitions;
    }

    /**
     * The raw value of a field: the stored value, or the default from config.
     */
    public static function raw(string $key): mixed
    {
        static::$loaded ??= Cache::rememberForever(
            self::CACHE_KEY,
            fn (): array => static::query()->pluck('value', 'key')->all(),
        );

        if (array_key_exists($key, static::$loaded) && static::$loaded[$key] !== null) {
            return static::$loaded[$key];
        }

        return static::definitions()[$key]['default'] ?? null;
    }

    /**
     * The display value of a field in the current locale.
     */
    public static function text(string $key): string
    {
        $value = static::raw($key);

        return is_array($value) ? HasTranslations::pickTranslation($value) : (string) $value;
    }

    public static function put(string $key, mixed $value): void
    {
        static::query()->updateOrCreate(['key' => $key], ['value' => $value]);
    }
}
