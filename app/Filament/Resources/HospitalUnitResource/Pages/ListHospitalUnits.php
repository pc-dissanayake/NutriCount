<?php

namespace App\Filament\Resources\HospitalUnitResource\Pages;

use App\Filament\Resources\HospitalUnitResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListHospitalUnits extends ListRecords
{
    protected static string $resource = HospitalUnitResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
