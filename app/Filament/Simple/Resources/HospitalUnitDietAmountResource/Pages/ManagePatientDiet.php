<?php

namespace App\Filament\Simple\Resources\HospitalUnitDietAmountResource\Pages;

use Filament\Resources\Pages\Page;
use App\Models\Patient;
use App\Models\HospitalUnitDietAmount;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Card;
use Filament\Notifications\Notification;

class ManagePatientDiet extends Page
{
    protected static string $resource = \App\Filament\Simple\Resources\HospitalUnitDietAmountResource::class;
    protected static string $view = 'filament.simple.resources.hospital-unit-diet-amount-resource.pages.manage-patient-diet';

    public $patient_id;
    public $date;
    public $hospital_unit_id;
    public $simple_diet_id;
    public $amount;

    public function mount(): void
    {
        $this->form->fill();
    }

    protected function getFormSchema(): array
    {
        return [
            Card::make([
                Select::make('patient_id')
                    ->label('Patient')
                    ->options(Patient::pluck('full_name', 'id'))
                    ->searchable()
                    ->required(),
                DatePicker::make('date')
                    ->label('Date')
                    ->required(),
                Select::make('hospital_unit_id')
                    ->label('Hospital Unit')
                    ->relationship('hospitalUnit', 'name')
                    ->required(),
                Select::make('simple_diet_id')
                    ->label('Simple Diet')
                    ->relationship('simpleDiet', 'DietName_en')
                    ->required(),
                TextInput::make('amount')
                    ->label('Amount')
                    ->numeric()
                    ->required(),
            ])
        ];
    }

    public function save()
    {
        HospitalUnitDietAmount::create([
            'patient_id' => $this->patient_id,
            'date' => $this->date,
            'hospital_unit_id' => $this->hospital_unit_id,
            'simple_diet_id' => $this->simple_diet_id,
            'amount' => $this->amount,
        ]);
        Notification::make()
            ->title('Diet entry saved!')
            ->success()
            ->send();
        $this->reset(['patient_id', 'date', 'hospital_unit_id', 'simple_diet_id', 'amount']);
    }
}
