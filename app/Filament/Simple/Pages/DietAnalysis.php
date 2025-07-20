<?php

namespace App\Filament\Simple\Pages;

use Filament\Pages\Page;
use Symfony\Component\HttpKernel\Exception\NotAcceptableHttpException;
use App\Models\HospitalUnit;
use App\Models\HospitalUnitDietAmount;
use App\Models\SimpleDiet;

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
        $date = request()->query('date');
        $month = request()->query('month');
        $year = request()->query('year');

        if ($date) {
            $this->date = $date;
        } elseif ($month) {
            $this->date = $month;
        } elseif ($year) {
            $this->date = $year;
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
}
