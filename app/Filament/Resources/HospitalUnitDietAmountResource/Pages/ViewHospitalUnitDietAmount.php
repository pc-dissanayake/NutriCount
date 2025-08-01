<?php

namespace App\Filament\Resources\HospitalUnitDietAmountResource\Pages;

use App\Filament\Resources\HospitalUnitDietAmountResource;
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
