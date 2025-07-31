<?php

namespace App\Filament\Simple\Resources\HospitalUnitDietAmountResource\Pages;

use App\Filament\Simple\Resources\HospitalUnitDietAmountResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\Section;

class ViewHospitalUnitDietAmount extends ViewRecord
{
    protected static string $resource = HospitalUnitDietAmountResource::class;

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make('Diet Amount Details')
                    ->schema([
                        TextEntry::make('date')
                            ->label('Date')
                            ->date(),
                        TextEntry::make('hospitalUnit.name')
                            ->label('Hospital Unit'),
                        TextEntry::make('simpleDiet.DietName_en')
                            ->label('Simple Diet'),
                        TextEntry::make('patient.full_name')
                            ->label('Patient')
                            ->formatStateUsing(fn ($state, $record) => 
                                is_null($record->patient_id) ? 'Ward Cumulative' : ($state ?: 'No Patient')
                            ),
                        TextEntry::make('amount')
                            ->label('Amount')
                            ->formatStateUsing(fn ($state) => $state ?? 'Not specified'),
                    ])
                    ->columns(2),
            ]);
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
