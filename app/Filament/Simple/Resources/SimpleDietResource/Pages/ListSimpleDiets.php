<?php

namespace App\Filament\Simple\Resources\SimpleDietResource\Pages;

use App\Filament\Simple\Resources\SimpleDietResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSimpleDiets extends ListRecords
{
    protected static string $resource = SimpleDietResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
