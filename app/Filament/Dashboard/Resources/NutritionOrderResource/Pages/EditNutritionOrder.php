<?php

namespace App\Filament\Dashboard\Resources\NutritionOrderResource\Pages;

use App\Filament\Dashboard\Resources\NutritionOrderResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditNutritionOrder extends EditRecord
{
    protected static string $resource = NutritionOrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
