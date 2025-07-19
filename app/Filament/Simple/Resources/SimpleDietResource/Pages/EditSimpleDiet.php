<?php

namespace App\Filament\Simple\Resources\SimpleDietResource\Pages;

use App\Filament\Simple\Resources\SimpleDietResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSimpleDiet extends EditRecord
{
    protected static string $resource = SimpleDietResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
