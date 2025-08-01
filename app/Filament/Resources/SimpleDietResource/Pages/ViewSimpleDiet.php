<?php

namespace App\Filament\Resources\SimpleDietResource\Pages;

use App\Filament\Resources\SimpleDietResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewSimpleDiet extends ViewRecord
{
    protected static string $resource = SimpleDietResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
