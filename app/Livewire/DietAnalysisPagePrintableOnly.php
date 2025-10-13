<?php

namespace App\Livewire;

use Livewire\Component;

use Symfony\Component\HttpKernel\Exception\NotAcceptableHttpException;
use App\Models\HospitalUnit;
use App\Models\HospitalUnitDietAmount;
use App\Models\SimpleDiet;
use Illuminate\Support\Facades\Auth;
use App\Models\Setting;

class DietAnalysisPagePrintableOnly extends Component
{
    public function render()
    {
        return view('livewire.diet-analysis-page-printable-only');
    }


      public $date;
    public $units;
    public $dietData;
    public $dietTypes;

    // Report/export settings
    public ?string $reportPageSize = null; // e.g., A4, A3, Letter
    public int $pageWidth = 210;  // mm
    public int $pageHeight = 297; // mm

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

        // Load all units ordered by order_id then name
        $this->units = HospitalUnit::orderBy('order_id')->orderBy('name')->get();

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

        // Read report page size from settings (category 'reports')
        $this->reportPageSize = Setting::get('reports.export_page_size', 'A4');
        [$this->pageWidth, $this->pageHeight] = $this->mapPageSizeToMm($this->reportPageSize);
    }

    protected function mapPageSizeToMm(?string $size): array
    {
        $size = strtoupper((string) $size);
        // Portrait dimensions in mm
        $map = [
            'A0' => [841, 1189],
            'A1' => [594, 841],
            'A2' => [420, 594],
            'A3' => [297, 420],
            'A4' => [210, 297],
            'A5' => [148, 210],
            'A6' => [105, 148],
            'B0' => [1000, 1414],
            'B1' => [707, 1000],
            'B2' => [500, 707],
            'B3' => [353, 500],
            'B4' => [250, 353],
            'B5' => [176, 250],
            'B6' => [125, 176],
            'LETTER' => [216, 279],
            'LEGAL' => [216, 356],
        ];

        return $map[$size] ?? $map['A4'];
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
