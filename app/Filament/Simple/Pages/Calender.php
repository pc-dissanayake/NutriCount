<?php

namespace App\Filament\Simple\Pages;

use Filament\Pages\Page;
use App\Models\HospitalUnitDietAmount;

class Calender extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.simple.pages.calender';

    public $data;

    protected $hospitalUnitDietAmounts = [];

    // public function mount(): void
    // {
    //     $this->data = \App\Models\YourModel::all();
    // }

    public function mount(): void
    {
        $this->hospitalUnitDietAmounts = HospitalUnitDietAmount::query()
            ->select('date')
            ->distinct()
            ->pluck('date')
            ->flip()
            ->toArray();

           // dd($this->hospitalUnitDietAmounts);
    }

    protected function getViewData(): array
    {
        return [
            'uniqueDates' => $this->getUniqueDates(),
        ];
    }

    public function getUniqueDates(): array
    {
        return $this->data?->toArray() ?? [];
    }
}
