<?php

namespace App\Filament\Simple\Resources\HospitalUnitDietAmountResource\Pages;

use App\Filament\Simple\Resources\HospitalUnitDietAmountResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListHospitalUnitDietAmounts extends ListRecords
{
    protected static string $resource = HospitalUnitDietAmountResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
