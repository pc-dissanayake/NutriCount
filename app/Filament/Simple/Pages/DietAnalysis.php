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

        if (!$date) {
            throw new NotAcceptableHttpException('Date parameter is required.');
        }

        $this->date = $date;

        // Load all units
        $this->units = HospitalUnit::all();

        // Load all diet types ordered by list_order
        $this->dietTypes = SimpleDiet::orderBy('list_order')->get();

        // Load diet data for the specified date
        $this->dietData = HospitalUnitDietAmount::where('date', $date)->get();
    }
}
