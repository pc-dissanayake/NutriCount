<?php

namespace App\Filament\Resources\HospitalUnitResource\Pages;

use App\Filament\Resources\HospitalUnitResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditHospitalUnit extends EditRecord
{
    protected static string $resource = HospitalUnitResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
