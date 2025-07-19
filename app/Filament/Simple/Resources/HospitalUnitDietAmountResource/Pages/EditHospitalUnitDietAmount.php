<?php

namespace App\Filament\Simple\Resources\HospitalUnitDietAmountResource\Pages;

use App\Filament\Simple\Resources\HospitalUnitDietAmountResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditHospitalUnitDietAmount extends EditRecord
{
    protected static string $resource = HospitalUnitDietAmountResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
