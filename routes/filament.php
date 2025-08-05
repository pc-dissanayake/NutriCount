<?php

use App\Filament\Simple\Pages\UnitDietEntry;
use App\Filament\Simple\Pages\PatientEntry;
use App\Filament\Simple\Pages\UnitDietLogs;
use App\Filament\Simple\Pages\HospitalUnitsDietsAmountSheet;
use Illuminate\Support\Facades\Route;

// // Register custom simple pages for the simple panel
// \Filament\Panel::make('simple')
//     ->pages([
//         UnitDietEntry::class,
//         PatientEntry::class,
//         UnitDietLogs::class,
//     ]);

Route::get('/hospital-units-diets-amount-sheet', HospitalUnitsDietsAmountSheet::class)->name('filament.pages.hospital-units-diets-amount-sheet');


