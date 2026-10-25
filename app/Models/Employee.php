<?php

namespace App\Models;

use App\Models\Concerns\CleansUpFiles;
use App\Models\Concerns\HasTranslations;
use App\Models\Concerns\IsSortable;
use Database\Factories\EmployeeFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * A person on the "who is who" page, with job title (per language), contact details and photo.
 */
#[Fillable(['name', 'job_title', 'email', 'phone', 'mobile', 'photo', 'active', 'sort'])]
class Employee extends Model
{
    /** @use HasFactory<EmployeeFactory> */
    use CleansUpFiles, HasFactory, HasTranslations, IsSortable;

    protected const FILE_ATTRIBUTES = ['photo'];

    /**
     * Whether the "who is who" page is shown: switched on in the settings and with at least one visible person.
     */
    public static function pageIsAvailable(): bool
    {
        return content_enabled('pages.who_is_who') && static::query()->where('active', true)->exists();
    }

    /**
     * Phone number as a tel: link target, e.g. "+3241234567".
     */
    public static function telLink(?string $number): ?string
    {
        return filled($number) ? 'tel:'.preg_replace('/[^+\d]/', '', $number) : null;
    }

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'job_title' => 'array',
            'active' => 'boolean',
        ];
    }
}
