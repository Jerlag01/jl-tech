<?php

namespace App\Models\Concerns;

use Illuminate\Support\Facades\Storage;

/**
 * Deletes uploaded files from the public disk when they are replaced or their record is deleted.
 * Models list their upload columns in the FILE_ATTRIBUTES constant.
 */
trait CleansUpFiles
{
    protected static function bootCleansUpFiles(): void
    {
        static::updated(function (self $model): void {
            foreach (static::FILE_ATTRIBUTES as $attribute) {
                $old = $model->getOriginal($attribute);

                if (filled($old) && $old !== $model->getAttribute($attribute)) {
                    Storage::disk('public')->delete($old);
                }
            }
        });

        static::deleted(function (self $model): void {
            foreach (static::FILE_ATTRIBUTES as $attribute) {
                if (filled($model->getAttribute($attribute))) {
                    Storage::disk('public')->delete($model->getAttribute($attribute));
                }
            }
        });
    }
}
