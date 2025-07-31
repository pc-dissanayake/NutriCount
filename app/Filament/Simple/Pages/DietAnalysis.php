<?php

namespace App\Filament\Simple\Pages;

use Filament\Pages\Page;
use Symfony\Component\HttpKernel\Exception\NotAcceptableHttpException;
use App\Models\HospitalUnit;
use App\Models\HospitalUnitDietAmount;
use App\Models\SimpleDiet;
use Illuminate\Support\Facades\Auth;

class DietAnalysis extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.simple.pages.diet-analysis';

    protected static ?string $title = 'NHSL Total Diet Analysis';

    protected static bool $shouldRegisterNavigation = false;
    

    public $date;
    public $units;
    public $dietData;
    public $dietTypes;

    public function mount(): void
    {
        $user = Auth::user();
        
        $date = request()->query('date');
        $month = request()->query('month');
        $year = request()->query('year');

        // Check permissions based on what data is being requested
        if ($date) {
            // Daily view - check daily permission
            if (!$user || !userHasPermission($user, 'view.daily_diet_analysis_calender_simple-panel')) {
                abort(403, 'Access denied. You do not have permission to view daily diet analysis.');
            }
            $this->date = $date;
        } elseif ($month || $year) {
            // Monthly/yearly view - check monthly/yearly permissions
            if (!$user || !(userHasPermission($user, 'view.monthly_diet_analysis_calender_simple-panel') || 
                           userHasPermission($user, 'view.monthly_calender_simple-panel') ||
                           userHasPermission($user, 'view.yearly_diet_analysis_calender_simple-panel'))) {
                abort(403, 'Access denied. You do not have permission to view monthly/yearly diet analysis.');
            }
            $this->date = $month ?: $year;
        } else {
            throw new NotAcceptableHttpException('Date, month, or year parameter is required.');
        }

        // Load all units
        $this->units = HospitalUnit::all();

        // Load all diet types ordered by list_order
        $this->dietTypes = SimpleDiet::orderBy('list_order')->get();

        // Load diet data for the specified date/month/year
        if ($date) {
            $this->dietData = HospitalUnitDietAmount::where('date', $date)->get();
        } elseif ($month) {
            $this->dietData = HospitalUnitDietAmount::where('date', 'like', $month . '%')->get();

            //dd($this->dietData);
        } elseif ($year) {
            $this->dietData = HospitalUnitDietAmount::where('date', 'like', $year . '%')->get();
        } else {
            $this->dietData = collect();
        }
    }

    public static function canAccess(): bool
    {
        $user = Auth::user();
        if (!$user) {
            return false;
        }

        // Check if user has any of the required permissions
        return userHasPermission($user, 'view.daily_diet_analysis_calender_simple-panel') ||
               userHasPermission($user, 'view.monthly_diet_analysis_calender_simple-panel') ||
               userHasPermission($user, 'view.monthly_calender_simple-panel') ||
               userHasPermission($user, 'view.yearly_diet_analysis_calender_simple-panel');
    }
}
