<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;
use App\Http\Controllers\UnitDietEntryController;
use App\Http\Controllers\WelcomeController;
use App\Http\Middleware\CheckUserActive;

    Route::get('/', [WelcomeController::class, 'index'])->name('home');

    Route::get('/dashboard', fn () => redirect()->route('filament.dashboard.pages.dashboard'))->name('dashboard');



    Route::middleware(['auth'
    //,'CheckUserActive'
    ])->group(function () {
        Route::redirect('settings', 'settings/profile');

        Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
        Volt::route('settings/password', 'settings.password')->name('settings.password');
        Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');
    });

    Route::post('/simple/unit-diet-entry/save', [UnitDietEntryController::class, 'saveDietAmounts'])->name('unit-diet-entry.save');

    Route::post('/simple/paient-individual-diet/save', [UnitDietEntryController::class, 'saveIndividualDietAmounts'])->name('paient-individual-diet.save');
require __DIR__.'/auth.php';
