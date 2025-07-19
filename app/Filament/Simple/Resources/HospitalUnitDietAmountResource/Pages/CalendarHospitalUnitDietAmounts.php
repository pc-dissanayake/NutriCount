<?php

namespace App\Filament\Simple\Resources\HospitalUnitDietAmountResource\Pages;

use App\Filament\Simple\Resources\HospitalUnitDietAmountResource;
use Filament\Resources\Pages\Page;

class CalendarHospitalUnitDietAmounts extends Page
{
    protected static string $resource = HospitalUnitDietAmountResource::class;
    protected static string $view = 'filament.simple.resources.hospital-unit-diet-amount-resource.pages.calendar-hospital-unit-diet-amounts';
}
