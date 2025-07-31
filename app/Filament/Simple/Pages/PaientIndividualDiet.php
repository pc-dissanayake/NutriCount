<?php

namespace App\Filament\Simple\Pages;

use App\Models\HospitalUnit;
use App\Models\HospitalUnitDietAmount;
use App\Models\SimpleDiet;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;

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
        $user = Auth::user();
        
        // Check if user has permission to add individual diet data
        if (!userHasPermission($user, 'add_individual_diet_data.Patient')) {
            abort(403, 'You do not have permission to add individual diet data.');
        }

        // Check unit access permissions
        $hasAccessToAllUnits = userHasPermission($user, 'list_all.unit-simple_panel');
        $hasViewUnitPermission = userHasPermission($user, 'view.unit-simple_panel');
        
        if (!$hasAccessToAllUnits && !$hasViewUnitPermission) {
            abort(403, 'You do not have permission to access unit data.');
        }

        $this->date = request('date');
        $this->unitId = request('unit_id');
        $this->selected_patient_id = request('patient_id');

        // Load only the selected unit and its patients
        if ($this->unitId) {
            $unit = \App\Models\HospitalUnit::with('patients')->find($this->unitId);
            
            // Check if user has access to this specific unit
            if ($unit && !$hasAccessToAllUnits) {
                // If user doesn't have access to all units, check if this unit is assigned to them
                $assignedUnitIds = $user->units_assigned ?? [];
                if (!in_array($this->unitId, $assignedUnitIds)) {
                    abort(403, 'You do not have access to this unit.');
                }
            }
            
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
