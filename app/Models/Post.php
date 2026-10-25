<?php

namespace App\Models;

use App\Models\Concerns\CleansUpFiles;
use App\Models\Concerns\HasTranslations;
use Database\Factories\PostFactory;
use Filament\Forms\Components\RichEditor\RichContentRenderer;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

/**
 * A news post. Without a publish date it is a draft; a future date schedules it.
 */
#[Fillable(['user_id', 'slug', 'title', 'excerpt', 'body', 'image', 'published_at'])]
class Post extends Model
{
    /** @use HasFactory<PostFactory> */
    use CleansUpFiles, HasFactory, HasTranslations;

    protected const FILE_ATTRIBUTES = ['image'];

    /**
     * @return BelongsTo<User, $this>
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * @param  Builder<self>  $query
     */
    public function scopePublished(Builder $query): void
    {
        $query->whereNotNull('published_at')->where('published_at', '<=', now());
    }

    /**
     * @param  Builder<self>  $query
     */
    public function scopeLatestPublished(Builder $query): void
    {
        $query->published()->latest('published_at')->latest('id');
    }

    public function isPublished(): bool
    {
        return $this->published_at !== null && $this->published_at->isPast();
    }

    /**
     * Whether the news page is shown: switched on in the settings and with at least one published post.
     */
    public static function pageIsAvailable(): bool
    {
        return content_enabled('pages.news') && static::query()->published()->exists();
    }

    /**
     * The body in the current locale as sanitized HTML.
     */
    public function bodyHtml(): string
    {
        return RichContentRenderer::make($this->tr('body'))->toHtml();
    }

    /**
     * The excerpt in the current locale, or a shortened plain-text body.
     */
    public function summary(): string
    {
        return $this->tr('excerpt') ?: Str::limit(strip_tags($this->tr('body')), 180);
    }

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'title' => 'array',
            'excerpt' => 'array',
            'body' => 'array',
            'published_at' => 'datetime',
        ];
    }
}
