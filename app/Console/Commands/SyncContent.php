<?php

namespace App\Console\Commands;

use App\Models\Content;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('content:sync {--reset : Overwrite saved values with the defaults}')]
#[Description('Store the default value of every field in config/content.php that has no saved value yet')]
class SyncContent extends Command
{
    public function handle(): int
    {
        $saved = Content::query()->pluck('key')->all();
        $count = 0;

        foreach (Content::definitions() as $key => $field) {
            if (in_array($key, $saved, true) && ! $this->option('reset')) {
                continue;
            }

            Content::put($key, $field['default'] ?? ($field['type'] === 'image' ? '' : null));
            $count++;
        }

        $this->components->info("Stored {$count} content field(s).");

        return self::SUCCESS;
    }
}
