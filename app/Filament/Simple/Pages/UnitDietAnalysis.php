<?php

namespace App\Filament\Simple\Pages;

use Filament\Pages\Page;
use App\Models\HospitalUnit;
use App\Models\HospitalUnitDietAmount;
use App\Models\SimpleDiet;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UnitDietAnalysis extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';

    protected static string $view = 'filament.simple.pages.unit-diet-analysis';

    protected static ?string $title = 'Unit Diet Analysis';
    
    protected static bool $shouldRegisterNavigation = false;


    public $unitId;
    public $startDate;
    public $endDate;
    public $units;
    public $dietData;
    public $dietTypes;

    public function mount(): void
    {
        $user = Auth::user();

         if (!$user || !userHasPermission($user, 'view.unit_diet_analysis_simple-panel')) {
                abort(403, 'Access denied. You do not have permission to view unit diet analysis.');
            }

        // Load all units
        $this->units = HospitalUnit::all();

        // Load all diet types ordered by list_order
        $this->dietTypes = SimpleDiet::orderBy('list_order')->get();

        // Get query parameters
        $this->unitId = request()->query('unit_id');
        $this->startDate = request()->query('start_date');
        $this->endDate = request()->query('end_date');

        // Validate input
        if (!$this->unitId || !$this->startDate || !$this->endDate) {
            $this->dietData = collect();
            return;
        }

        // Fetch diet data for the selected unit and date range
        $this->dietData = HospitalUnitDietAmount::where('hospital_unit_id', $this->unitId)
            ->whereBetween('date', [$this->startDate, $this->endDate])
            ->get();
    }

    public static function canAccess(): bool
    {
        $user = Auth::user();
                if (!$user) {
            return false;
        }

        return $user && userHasPermission($user, 'view.unit_diet_analysis_simple-panel');
    }
}
