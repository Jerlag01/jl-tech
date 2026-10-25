<?php

namespace App\Models;

use App\Models\Concerns\HasTranslations;
use App\Models\Concerns\IsSortable;
use Database\Factories\FooterLinkFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * A custom link shown in its own row in the website footer, e.g. "Terms and conditions" or a partner site.
 */
#[Fillable(['label', 'url', 'open_in_new_tab', 'active', 'sort'])]
class FooterLink extends Model
{
    /** @use HasFactory<FooterLinkFactory> */
    use HasFactory, HasTranslations, IsSortable;

    /**
     * Web addresses (http/https), site paths ("/nl/contact"), e-mail and phone links.
     * Anything else (e.g. "javascript:") is refused, so a link can never run script.
     */
    public const URL_PATTERN = '/^(https?:\/\/[^\s]+|\/[^\s\/][^\s]*|\/|mailto:[^\s]+|tel:\+?[\d\s\-()]+)$/i';

    /**
     * The visible links for the footer, in their admin order.
     *
     * @return Collection<int, self>
     */
    public static function forFooter(): Collection
    {
        return static::query()->visible()->get();
    }

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'label' => 'array',
            'open_in_new_tab' => 'boolean',
            'active' => 'boolean',
        ];
    }
}
