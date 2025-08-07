<?php

namespace App\Filament\Simple\Pages;

use App\Models\HospitalUnit;
use App\Models\HospitalUnitDietAmount;
use App\Models\SimpleDiet;
use Filament\Pages\Page;
use Filament\Notifications\Notification;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class HospitalUnitsDietsAmountSheet extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.simple.pages.hospital-units-diets-amount-sheet';
    protected static bool $shouldRegisterNavigation = false;

    public   $date;
    public   $tabMovement;
    public   $defaultLanguage;

    public Collection $units;
    public Collection $diets;
    public array $amounts = [];
    public int $moveCount = 0;

    public function mount(): void
    {
        $user = Auth::user();

        if (!userHasPermission($user, 'view.unit-simple_panel') && !userHasPermission($user, 'list_all.unit-simple_panel')) {
            abort(403, 'Access denied. You do not have permission to view the sheet.');
        }

        // Set date from request only if not already set (so Livewire keeps it in sync after actions)
        if (!$this->date) {
            $this->date = request()->query('date');
        }

        // $this->units = HospitalUnit::where('active', true)->orderBy('order_id')->get();

        // Check if user has permission to list all units
        if (userHasPermission($user, 'list_all.unit-simple_panel')) {
            // Load all units
            $this->units = HospitalUnit::where('active', true)->orderBy('order_id')->orderBy('name')->get();
        } else {
            // Load only units assigned to the user
            $assignedUnitIds = $user->units_assigned ?? [];
            if (empty($assignedUnitIds)) {
                $this->units = collect([]);
            } else {
                $this->units = HospitalUnit::query()
                    ->whereIn('id', $assignedUnitIds)
                    ->orderBy('order_id')
                    ->orderBy('name')
                    ->where('active',true)
                    ->get();
            }
        }

        $this->diets = SimpleDiet::where('active', true)->orderBy('list_order')->get();

        // Clean the date to remove any query parameters that might be appended
        if ($this->date && strpos($this->date, '?') !== false) {
            $this->date = substr($this->date, 0, strpos($this->date, '?'));
        }

        // Get tab movement from URL parameter (LR = Left to Right, TB = Top to Bottom)
        // Use user's default if no URL parameter is provided
        $this->tabMovement = request()->query('tabmove', $user->default_movement ?? 'LR');

        // Set default language from user preference if available
        $this->defaultLanguage = $user->default_lang;

        foreach ($this->units as $unit) {
            foreach ($this->diets as $diet) {
                $existing = HospitalUnitDietAmount::where('date', $this->date)->where('hospital_unit_id', $unit->id)->where('simple_diet_id', $diet->id)->first();
                $this->amounts[$unit->id][$diet->id] = $existing?->amount ?? '';
            }
        }
    }

    public function autoPopulateFromYesterday($selectedDate = null)
    {
        $user = Auth::user();
        
        if (!userHasPermission($user, 'populate.unit_diet_amounts-simple_panel')) {
            Notification::make()
                ->title('Access denied. You do not have permission to auto-populate.')
                ->danger()
                ->send();
            return;
        }

        if (empty($this->date)) {
            Notification::make()
                ->title('Date is required to auto populate.')
                ->danger()
                ->send();
            return;
        }

        $baseDate = $selectedDate ?? date('Y-m-d', strtotime($this->date . ' -1 day'));
        $populatedCount = 0;
        $populatedDetails = [];

        foreach ($this->units as $unit) {
            foreach ($this->diets as $diet) {
                if (!($diet->auto_populate ?? false)) {
                    continue;
                }
                $prev = HospitalUnitDietAmount::where('date', $baseDate)
                    ->where('hospital_unit_id', $unit->id)
                    ->where('simple_diet_id', $diet->id)
                    ->whereNull('patient_id')
                    ->first();
                if ($prev) {
                    $this->amounts[$unit->id][$diet->id] = $prev->amount;
                    $populatedCount++;
                    $populatedDetails[] = [
                        'unit' => $unit->name,
                        'diet' => $diet->DietName_en,
                        'amount' => $prev->amount
                    ];
                }
            }
        }

        // Log the auto-populate action using Spatie Activity Log
        if ($populatedCount > 0) {
            \Spatie\Activitylog\Models\Activity::create([
                'log_name' => 'diet_amounts_auto_populate',
                'description' => "Auto-populated diet amounts from {$baseDate} to {$this->date} - {$populatedCount} records populated",
                'subject_type' => 'App\Models\HospitalUnitDietAmount',
                'subject_id' => Str::uuid()->toString(),
                'event' => 'updated',
                'causer_id' => Auth::id(),
                'causer_type' => Auth::user()::class,
                'properties' => [
                    'target_date' => $this->date,
                    'source_date' => $baseDate,
                    'populated_count' => $populatedCount,
                    'details' => $populatedDetails
                ],
            ]);
        }

        Notification::make()
            ->title('Auto populated from selected day!')
            ->success()
            ->send();
        $this->dispatch('saved');
    }

    public function save()
    {
        if (empty($this->date)) {
            Notification::make()
                ->title('Date is required to save amounts.')
                ->danger()
                ->send();
            return;
        }

        $changedRecords = 0;
        $logDetails = [];
        foreach ($this->amounts as $unitId => $diets) {
            foreach ($diets as $dietId => $amount) {
                // Skip empty strings and null values
                if (!is_null($amount) && $amount !== '') {
                    // Check if this is a new record or update
                    $existing = HospitalUnitDietAmount::where('date', $this->date)
                        ->where('hospital_unit_id', $unitId)
                        ->where('simple_diet_id', $dietId)
                        ->first();
                    
                    if (!$existing || $existing->amount != $amount) {
                        $record = HospitalUnitDietAmount::updateOrCreate(
                            ['hospital_unit_id' => $unitId, 'simple_diet_id' => $dietId, 'date' => $this->date],
                            ['amount' => $amount]
                        );
                        
                        $unit = $this->units->firstWhere('id', $unitId);
                        $diet = $this->diets->firstWhere('id', $dietId);
                        $logDetails[] = [
                            'unit' => $unit?->name ?? 'Unknown',
                            'diet' => $diet?->DietName_en ?? 'Unknown',
                            'old_amount' => $existing?->amount ?? 0,
                            'new_amount' => $amount,
                            'action' => $existing ? 'updated' : 'created'
                        ];
                        $changedRecords++;
                    }
                }
            }
        }

        // Log the changes using Spatie Activity Log
        if (!empty($logDetails)) {
            \Spatie\Activitylog\Models\Activity::create([
                'log_name' => 'diet_amounts_save',
                'description' => "Saved diet amounts for {$this->date} - {$changedRecords} records changed",
                'subject_type' => 'App\Models\HospitalUnitDietAmount',
                'subject_id' => Str::uuid()->toString(),
                'event' => 'updated',
                'causer_id' => Auth::id(),
                'causer_type' => Auth::user()::class,
                'properties' => [
                    'date' => $this->date,
                    'changed_records' => $changedRecords,
                    'details' => $logDetails
                ],
            ]);
        }

        Notification::make()
            ->title("Amounts saved successfully! ($changedRecords records changed)")
            ->success()
            ->send();
            
        $this->dispatch('saved');
    }

    public function autoSave()
    {
        if (empty($this->date)) {
            return;
        }

        foreach ($this->amounts as $unitId => $diets) {
            foreach ($diets as $dietId => $amount) {
                // Skip empty strings and null values
                if (!is_null($amount) && $amount !== '') {
                    HospitalUnitDietAmount::updateOrCreate(
                        ['hospital_unit_id' => $unitId, 'simple_diet_id' => $dietId, 'date' => $this->date],
                        ['amount' => $amount]
                    );
                }
            }
        }

        // Reset move count after auto-save
        $this->moveCount = 0;

        Notification::make()
            ->title('Auto-saved!')
            ->success()
            ->duration(2000)
            ->send();
    }

    public function updateUserMovementPreference($movement)
    {
        $user = Auth::user();
        $user->update(['default_movement' => $movement]);
        
        Notification::make()
            ->title('Movement preference saved!')
            ->success()
            ->duration(1500)
            ->send();
    }

    public function clearAll()
    {
        $user = Auth::user();
        
        if (!userHasPermission($user, 'clear_all.unit_diet_amounts-simple_panel')) {
            Notification::make()
                ->title('Access denied. You do not have permission to clear all records.')
                ->danger()
                ->send();
            return;
        }

        if (empty($this->date)) {
            Notification::make()
                ->title('Date is required to clear records.')
                ->danger()
                ->send();
            return;
        }

        // Count records before deletion for logging
        $deletedCount = HospitalUnitDietAmount::where('date', $this->date)->count();
        
        // Delete all records for this date
        HospitalUnitDietAmount::query()->where('date', $this->date)->delete();

        // Clear the amounts array
        foreach ($this->units as $unit) {
            foreach ($this->diets as $diet) {
                $this->amounts[$unit->id][$diet->id] = '';
            }
        }

        // Log the clear action using Spatie Activity Log
        \Spatie\Activitylog\Models\Activity::create([
            'log_name' => 'diet_amounts_clear',
            'description' => "Cleared all diet amounts for {$this->date} - {$deletedCount} records deleted",
            'subject_type' => 'App\Models\HospitalUnitDietAmount',
            'subject_id' => Str::uuid()->toString(),
            'event' => 'updated',
            'causer_id' => Auth::id(),
            'causer_type' => Auth::user()::class,
            'properties' => [
                'date' => $this->date,
                'deleted_records' => $deletedCount
            ],
        ]);

        Notification::make()
            ->title('All records cleared successfully!')
            ->success()
            ->send();
        
        $this->dispatch('saved');
    }

}
