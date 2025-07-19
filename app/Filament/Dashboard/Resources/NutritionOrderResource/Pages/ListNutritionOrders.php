<?php

namespace App\Filament\Dashboard\Resources\NutritionOrderResource\Pages;

use App\Filament\Dashboard\Resources\NutritionOrderResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListNutritionOrders extends ListRecords
{
    protected static string $resource = NutritionOrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
