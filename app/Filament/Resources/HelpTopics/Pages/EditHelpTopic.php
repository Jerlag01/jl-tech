<?php

namespace App\Filament\Resources\HelpTopics\Pages;

use App\Filament\Resources\HelpTopics\HelpTopicResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditHelpTopic extends EditRecord
{
    protected static string $resource = HelpTopicResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
