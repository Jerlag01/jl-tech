<?php

namespace App\Models;

use App\Models\Concerns\CleansUpFiles;
use App\Models\Concerns\IsSortable;
use Database\Factories\ClientFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['name', 'logo', 'url', 'in_marquee', 'in_logo_grid', 'active', 'sort'])]
class Client extends Model
{
    /** @use HasFactory<ClientFactory> */
    use CleansUpFiles, HasFactory, IsSortable;

    protected const FILE_ATTRIBUTES = ['logo'];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'in_marquee' => 'boolean',
            'in_logo_grid' => 'boolean',
            'active' => 'boolean',
        ];
    }
}
