<?php

namespace App\Filament\Simple\Pages;

use Filament\Pages\Page;
use App\Models\HospitalUnit;
use Illuminate\Support\Facades\Auth;

class Unit extends Page
{
    protected static ?string $navigationIcon = 'bx-food-menu';

    protected static string $view = 'filament.simple.pages.unit';

    protected static ?string $title = 'Hospital Units Diet Overview';

    public $unitData = [];

    public function mount()
    {
        
        $user = Auth::user();
        //dd(userHasPermission($user, 'view.unit-simple_panel'));
        if (!userHasPermission($user, 'view.unit-simple_panel') && !userHasPermission($user, 'list_all.unit-simple_panel')) {
            abort(403, 'Access denied. You do not have permission to view the units.');
        }

        if (!static::hasDateTag()) {
            return redirect()->to(route('filament.simple.pages.unit', ['date' => now()->toDateString()]));
        }

        // Load units based on permissions
        $date = request('date');
        
        // Check if user has permission to list all units
        if (userHasPermission($user, 'list_all.unit-simple_panel')) {
            // Load all units
            $units = HospitalUnit::query()->orderBy('order_id')->where('active',true)->pluck('name', 'id')->toArray();
        } else {
            // Load only units assigned to the user
            $assignedUnitIds = $user->units_assigned ?? [];
            if (empty($assignedUnitIds)) {
                $units = [];
            } else {
                $units = HospitalUnit::query()
                    ->whereIn('id', $assignedUnitIds)
                    ->orderBy('order_id')
                    ->where('active',true)
                    ->pluck('name', 'id')
                    ->toArray();
            }
        }
        
        $this->unitData = [];
        foreach ($units as $id => $name) {
            $dataAvailable = \App\Models\HospitalUnitDietAmount::where('hospital_unit_id', $id)
                ->where('date', $date)
                ->exists();
            $this->unitData[] = [
                'id' => $id,
                'name' => $name,
                'dataavailable' => $dataAvailable,
            ];
        }

       // dd( $this->unitData);
    }

    public static function hasDateTag(): bool
    {
        // Accept if ?date=... is present in the query string
        if (request()->has('date') && !empty(request()->query('date'))) {
            return true;
        }
        $tags = static::$tags ?? [];
        foreach ($tags as $tag) {
            if (is_string($tag) && $tag === 'date=date') {
                return true;
            }
            if (is_array($tag) && (isset($tag['date']) && $tag['date'] === 'date')) {
                return true;
            }
        }
        return false;
    }
}
