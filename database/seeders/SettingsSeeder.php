<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $defaultSettings = [
            // Application Settings
            [
                'key' => 'app.name',
                'value' => 'NutriCount',
                'type' => 'string',
                'category' => 'app',
                'description' => 'Application name',
                'is_public' => true,
            ],
            [
                'key' => 'app.hospital_name',
                'value' => 'General Hospital',
                'type' => 'string',
                'category' => 'app',
                'description' => 'Hospital name',
                'is_public' => true,
            ],
            [
                'key' => 'app.description',
                'value' => 'Nutrition management system for hospitals',
                'type' => 'string',
                'category' => 'app',
                'description' => 'Application description',
                'is_public' => true,
            ],
            [
                'key' => 'app.timezone',
                'value' => 'Asia/Colombo',
                'type' => 'string',
                'category' => 'app',
                'description' => 'Application timezone',
                'is_public' => false,
            ],

            // Nutrition Settings
            [
                'key' => 'nutrition.auto_calculate',
                'value' => '1',
                'type' => 'boolean',
                'category' => 'nutrition',
                'description' => 'Auto calculate nutrition values',
                'is_public' => false,
            ],
            [
                'key' => 'nutrition.default_serving_size',
                'value' => '100',
                'type' => 'integer',
                'category' => 'nutrition',
                'description' => 'Default serving size in grams',
                'is_public' => false,
            ],
            [
                'key' => 'nutrition.calculation_method',
                'value' => 'standard',
                'type' => 'string',
                'category' => 'nutrition',
                'description' => 'Nutrition calculation method',
                'is_public' => false,
            ],

            // Unit Settings
            [
                'key' => 'units.auto_assign_diets',
                'value' => '0',
                'type' => 'boolean',
                'category' => 'units',
                'description' => 'Auto assign diets to new units',
                'is_public' => false,
            ],
            [
                'key' => 'units.default_capacity',
                'value' => '20',
                'type' => 'integer',
                'category' => 'units',
                'description' => 'Default unit capacity',
                'is_public' => false,
            ],
            [
                'key' => 'units.default_room_type',
                'value' => 'general',
                'type' => 'string',
                'category' => 'units',
                'description' => 'Default room type',
                'is_public' => false,
            ],

            // Report Settings
            [
                'key' => 'reports.enable_export',
                'value' => '1',
                'type' => 'boolean',
                'category' => 'reports',
                'description' => 'Enable export functionality',
                'is_public' => false,
            ],
            [
                'key' => 'reports.default_format',
                'value' => 'excel',
                'type' => 'string',
                'category' => 'reports',
                'description' => 'Default export format',
                'is_public' => false,
            ],
            [
                'key' => 'reports.export_page_size',
                'value' => 'A4',
                'type' => 'string',
                'category' => 'reports',
                'description' => 'Report page export size (e.g., A4, A3, Letter)',
                'is_public' => false,
            ],
            [
                'key' => 'reports.records_per_page',
                'value' => '25',
                'type' => 'integer',
                'category' => 'reports',
                'description' => 'Records per page in reports',
                'is_public' => false,
            ],

            // Security Settings
            [
                'key' => 'security.enable_activity_log',
                'value' => '1',
                'type' => 'boolean',
                'category' => 'security',
                'description' => 'Enable activity logging',
                'is_public' => false,
            ],
            [
                'key' => 'security.session_timeout',
                'value' => '120',
                'type' => 'integer',
                'category' => 'security',
                'description' => 'Session timeout in minutes',
                'is_public' => false,
            ],
            [
                'key' => 'security.require_password_change',
                'value' => '1',
                'type' => 'boolean',
                'category' => 'security',
                'description' => 'Require password change on first login',
                'is_public' => false,
            ],
            [
                'key' => 'security.password_min_length',
                'value' => '8',
                'type' => 'integer',
                'category' => 'security',
                'description' => 'Minimum password length',
                'is_public' => false,
            ],
        ];

        foreach ($defaultSettings as $setting) {
            Setting::firstOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }
}
