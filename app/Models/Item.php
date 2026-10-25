<?php

namespace App\Models;

use App\Models\Concerns\HasTranslations;
use App\Models\Concerns\IsSortable;
use Database\Factories\ItemFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

#[Fillable(['group', 'value', 'title', 'subtitle', 'body', 'list', 'icon', 'image', 'active', 'sort'])]
class Item extends Model
{
    /** @use HasFactory<ItemFactory> */
    use HasFactory, HasTranslations, IsSortable;

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'title' => 'array',
            'subtitle' => 'array',
            'body' => 'array',
            'list' => 'array',
            'active' => 'boolean',
        ];
    }

    /**
     * @param  Builder<Item>  $query
     */
    public function scopeInGroup(Builder $query, string $group): void
    {
        $query->where('group', $group);
    }

    /**
     * Active items of a group, in display order.
     *
     * @return Collection<int, Item>
     */
    public static function forGroup(string $group): Collection
    {
        return static::query()->inGroup($group)->visible()->get();
    }
}
