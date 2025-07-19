<?php

namespace App\Filament\Dashboard\Pages;

use Filament\Pages\Page;

class Dashboard extends Page
{
    protected static ?string $navigationIcon = 'fluentui-home-checkmark-20-o';

    protected static string $view = 'filament.dashboard.pages.dashboard';
}
