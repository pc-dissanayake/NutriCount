<?php

namespace App\Filament\Simple\Pages;

use Filament\Pages\Page;
use App\Models\HospitalUnitDietAmount;

class Calender extends Page
{
    protected static ?string $navigationIcon = 'heroicon-c-calendar-date-range';

    protected static string $view = 'filament.simple.pages.calender';

    public $data;

    protected $hospitalUnitDietAmounts = [];

    // public function mount(): void
    // {
    //     $this->data = \App\Models\YourModel::all();
    // }

    public function mount(): void
    {
        $month = request('month') ?? now()->format('Y-m');
        $this->hospitalUnitDietAmounts = HospitalUnitDietAmount::query()
            ->where('date', 'like', $month . '%')
            ->distinct()
            ->orderBy('date', 'desc')
            ->pluck('date')
            ->toArray();

         //   dd($this->hospitalUnitDietAmounts);
    }

    protected function getViewData(): array
    {
        return [
            'uniqueDates' => $this->getUniqueDates(),
            'hospitalUnitDietAmounts' => $this->hospitalUnitDietAmounts,
        ];
    }

    public function getUniqueDates(): array
    {
        return $this->data?->toArray() ?? [];
    }
}
