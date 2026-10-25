<?php

namespace App\Models;

use App\Models\Concerns\CleansUpFiles;
use App\Models\Concerns\HasTranslations;
use App\Models\Concerns\IsSortable;
use Database\Factories\ServiceFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['slug', 'title', 'subtitle', 'summary', 'description', 'features', 'stats', 'icon', 'image', 'show_on_home', 'active', 'sort'])]
class Service extends Model
{
    /** @use HasFactory<ServiceFactory> */
    use CleansUpFiles, HasFactory, HasTranslations, IsSortable;

    protected const FILE_ATTRIBUTES = ['image'];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'title' => 'array',
            'subtitle' => 'array',
            'summary' => 'array',
            'description' => 'array',
            'features' => 'array',
            'stats' => 'array',
            'show_on_home' => 'boolean',
            'active' => 'boolean',
        ];
    }

    /**
     * @return HasMany<Project, $this>
     */
    public function projects(): HasMany
    {
        return $this->hasMany(Project::class);
    }
}
