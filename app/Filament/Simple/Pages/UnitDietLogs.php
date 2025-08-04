<?php

namespace App\Filament\Simple\Pages;

use App\Models\HospitalUnit;
use App\Models\SimpleDiet;
use App\Models\HospitalUnitDietAmount;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Collection;
use Spatie\Activitylog\Models\Activity;

class UnitDietLogs extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    
    protected static string $view = 'filament.simple.pages.unit-diet-logs';
    
    protected static ?string $slug = 'unit-diet-logs';
    
    protected static ?string $navigationGroup = 'Diet Management';
    
    protected static ?int $navigationSort = 30;
    
    // Hide from navigation as it's accessed via links
    protected static bool $shouldRegisterNavigation = false;

    public $date;
    
    /** @var Collection<int, HospitalUnit> */
    public $units;
    
    /** @var Collection<int, Activity> */
    public $logs;

    public function mount(): void
    {
        $this->date = request('date');
        $user = Auth::user();
        
        // Check if user has permission to view logs
        if (!userHasPermission($user, 'log_view.unit-simple_panel')) {
            abort(403, 'Unauthorized to view logs');
        }
        
        // Load units based on user permissions
        if (userHasPermission($user, 'list_all.unit-simple_panel')) {
            $this->units = HospitalUnit::orderBy('name')->get();
        } else {
            $assignedUnitIds = $user->units_assigned ?? [];
            if (empty($assignedUnitIds)) {
                $this->units = HospitalUnit::whereRaw('1 = 0')->get();
            } else {
                $this->units = HospitalUnit::whereIn('id', $assignedUnitIds)
                    ->orderBy('name')
                    ->get();
            }
        }

        // Initialize logs as empty collection
        $this->logs = collect();

        $unitId = (string) request('unit_id', '');

        if (!empty($this->date) && !empty($unitId) && $this->units->where('id', $unitId)->isNotEmpty()) {
            $this->loadLogs($unitId);
        }
    }

    private function loadLogs($unitId): void
    {
        // Get all HospitalUnitDietAmount records for the unit and date
        $dietAmountIds = HospitalUnitDietAmount::where('hospital_unit_id', $unitId)
            ->where('date', $this->date)
            ->pluck('id');

        // Get activity logs for these records
        $this->logs = Activity::where('subject_type', HospitalUnitDietAmount::class)
            ->whereIn('subject_id', $dietAmountIds)
            ->with(['causer', 'subject'])
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($log) {
                // Get the subject (HospitalUnitDietAmount) with relationships
                $subject = $log->subject;
                if ($subject) {
                    $subject->load(['hospitalUnit', 'simpleDiet', 'patient']);
                }
                
                return $log;
            });
    }
}
