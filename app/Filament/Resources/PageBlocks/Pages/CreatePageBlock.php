<?php

namespace App\Filament\Resources\PageBlocks\Pages;

use App\Filament\Resources\PageBlocks\PageBlockResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePageBlock extends CreateRecord
{
    protected static string $resource = PageBlockResource::class;

    /**
     * Pre-select the section from the tab the user came from (?group=timeline).
     */
    protected function fillForm(): void
    {
        parent::fillForm();

        if (array_key_exists((string) request()->query('group'), config('site.item_groups'))) {
            $this->form->fill([...$this->form->getRawState(), 'group' => request()->query('group')]);
        }
    }
}
