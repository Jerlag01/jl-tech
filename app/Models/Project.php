<?php

namespace App\Models;

use App\Models\Concerns\HasTranslations;
use App\Models\Concerns\IsSortable;
use Database\Factories\ProjectFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['service_id', 'title', 'category', 'badge', 'description', 'stats', 'image', 'featured', 'highlight', 'show_on_home', 'active', 'sort'])]
class Project extends Model
{
    /** @use HasFactory<ProjectFactory> */
    use HasFactory, HasTranslations, IsSortable;

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'title' => 'array',
            'category' => 'array',
            'badge' => 'array',
            'description' => 'array',
            'stats' => 'array',
            'featured' => 'boolean',
            'highlight' => 'boolean',
            'show_on_home' => 'boolean',
            'active' => 'boolean',
        ];
    }

    /**
     * @return BelongsTo<Service, $this>
     */
    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }
}
