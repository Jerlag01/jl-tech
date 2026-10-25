<?php

namespace App\Filament\Resources\SlaPlans\Pages;

use App\Filament\Resources\SlaPlans\SlaPlanResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditSlaPlan extends EditRecord
{
    protected static string $resource = SlaPlanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
