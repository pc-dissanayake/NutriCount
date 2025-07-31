<?php

namespace App\Filament\Simple\Pages;

use App\Models\HospitalUnit;
use App\Models\HospitalUnitDietAmount;
use App\Models\SimpleDiet;
use Filament\Pages\Page;

class PaientIndividualDiet extends Page
   
{

    protected static bool $shouldRegisterNavigation = false;
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.simple.pages.paient-individual-diet';

    public $date;
    public $unitId;
    public $units;

    public $selected_patient_id;

    public $patients = [];

    public $simpleDiets = [];

    public function mount(): void
    {
        $this->date = request('date');
        $this->unitId = request('unit_id');
        $this->selected_patient_id = request('patient_id');

        // Load only the selected unit and its patients
        if ($this->unitId) {
            $unit = \App\Models\HospitalUnit::with('patients')->find($this->unitId);
            $this->units = $unit ? collect([$unit]) : collect();
            $this->patients = ($unit && isset($unit->patients)) ? $unit->patients : collect();
        } else {
            $this->units = collect();
            $this->patients = collect();
        }

        // Load all diets for the selected patient, unit, and date
        $this->simpleDiets = [];
        if ($this->date && $this->unitId && $this->selected_patient_id) {
            $allDiets = SimpleDiet::orderBy('list_order')->get();
            $this->simpleDiets = $allDiets->map(function ($diet) {
                $savedAmount = HospitalUnitDietAmount::where('hospital_unit_id', $this->unitId)
                    ->where('simple_diet_id', $diet->id)
                    ->where('patient_id', $this->selected_patient_id)
                    ->where('date', $this->date)
                    ->value('amount');
                $diet->saved_amount = $savedAmount ?? null;
                return $diet;
            });
        }
    }
        
    

    public function saveDietAmounts(array $dietAmounts): void
    {
        foreach ($dietAmounts as $dietId => $amount) {
            HospitalUnitDietAmount::updateOrCreate(
                [
                    'hospital_unit_id' => $this->unitId,
                    'simple_diet_id' => $dietId,
                    'patient_id' => $this->selected_patient_id,
                    'date' => $this->date,
                ],
                ['amount' => $amount]
            );
        }
    }
}
