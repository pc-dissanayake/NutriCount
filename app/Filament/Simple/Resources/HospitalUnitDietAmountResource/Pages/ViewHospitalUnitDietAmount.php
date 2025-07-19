<?php

namespace App\Filament\Simple\Resources\HospitalUnitDietAmountResource\Pages;

use App\Filament\Simple\Resources\HospitalUnitDietAmountResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewHospitalUnitDietAmount extends ViewRecord
{
    protected static string $resource = HospitalUnitDietAmountResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
