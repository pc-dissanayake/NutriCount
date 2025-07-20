<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use BezhanSalleh\PanelSwitch\PanelSwitch;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        PanelSwitch::configureUsing(function (PanelSwitch $panelSwitch) {
            // Custom configurations go here
            $panelSwitch->modalWidth('sm')
            ->slideOver()
            ->icons([
                'simple' => 'fluentui-food-grains-20',
                'admin' => 'heroicon-o-cog',
                'dashboard' => 'fluentui-home-checkmark-20-o',
            ], $asImage = false);
        });


        
    }
}
