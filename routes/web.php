<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;
use App\Http\Controllers\UnitDietEntryController;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\ActivityLogTestController;
use App\Http\Controllers\DietAmountController;

    Route::get('/', [WelcomeController::class, 'index'])->name('home');

    Route::get('/dashboard', fn () => redirect()->route('filament.dashboard.pages.dashboard'))->name('dashboard');



    Route::middleware(['auth'])->group(function () {
        Route::redirect('settings', 'settings/profile');

        Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
        Volt::route('settings/password', 'settings.password')->name('settings.password');
        Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');
        
        // Activity Log API routes
        Route::prefix('api/activity-logs')->group(function () {
            Route::get('/', [ActivityLogController::class, 'index'])->name('activity-logs.index');
            Route::get('/stats', [ActivityLogController::class, 'stats'])->name('activity-logs.stats');
            Route::post('/diet-amount', [ActivityLogController::class, 'createDietAmount'])->name('activity-logs.create-diet-amount');
            
            // Test routes (remove in production)
            Route::get('/test', [ActivityLogTestController::class, 'testLogging'])->name('activity-logs.test');
            Route::get('/demo-filters', [ActivityLogTestController::class, 'demonstrateFiltering'])->name('activity-logs.demo-filters');
        });
    });

    Route::post('/simple/unit-diet-entry/save', [UnitDietEntryController::class, 'saveDietAmounts'])->name('unit-diet-entry.save');

    Route::post('/simple/paient-individual-diet/save', [UnitDietEntryController::class, 'saveIndividualDietAmounts'])->name('paient-individual-diet.save');

    Route::post('/save-diet-amounts', [DietAmountController::class, 'save'])->name('save-diet-amounts');
require __DIR__.'/auth.php';
require __DIR__.'/filament.php';
