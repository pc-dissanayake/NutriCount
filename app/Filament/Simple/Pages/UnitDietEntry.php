<?php

namespace App\Filament\Simple\Pages;

use App\Models\HospitalUnit;
use App\Models\SimpleDiet;
use App\Models\HospitalUnitDietAmount;
use Filament\Pages\Page;

class UnitDietEntry extends Page
{
    protected static ?string $navigationIcon = 'fluentui-food-24';

    protected static string $view = 'filament.simple.pages.unit-diet-entry';

    public $date;

    public $units = [];

    public $simpleDiets = [];

    public function mount(): void
    {
        $this->date = request('date');
        $this->units = HospitalUnit::orderBy('name')->get();

        $unitId = request('unit_id');

        if (empty($this->date) || !$unitId || !$this->units->pluck('id')->contains($unitId)) {
            $this->date = null;
            $unitId = null;
        } else {
            $this->simpleDiets = SimpleDiet::orderBy('list_order')->get()->map(function ($diet) use ($unitId) {
                $savedAmount = HospitalUnitDietAmount::where('hospital_unit_id', $unitId)
                    ->where('simple_diet_id', $diet->id)
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
                    'hospital_unit_id' => request('unit_id'),
                    'simple_diet_id' => $dietId,
                    'date' => $this->date,
                ],
                ['amount' => $amount]
            );
        }
    }
}
