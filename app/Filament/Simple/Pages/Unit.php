<?php

namespace App\Filament\Simple\Pages;

use Filament\Pages\Page;
use App\Models\HospitalUnit;

class Unit extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.simple.pages.unit';

    // You can add properties and methods here to handle data and logic for your page.
    // For example, to fetch data from the database:
    // public $data;
    public $uniqueUnitIds = [];

    public function mount(): void
    {
        if (!static::hasDateTag()) {
            abort(406, 'Missing required date tag.');
        }

        // Load all units
        $this->uniqueUnitIds = HospitalUnit::query()
            ->pluck('name', 'id')
            ->toArray();
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
