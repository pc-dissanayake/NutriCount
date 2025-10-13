<?php

namespace App\Filament\Pages;

use App\Models\Setting;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Support\Exceptions\Halt;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;

class AppSettings extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';
    protected static string $view = 'filament.pages.app-settings';
    protected static ?string $title = 'App Settings';
    protected static ?string $navigationLabel = 'App Settings';
    protected static ?int $navigationSort = 100;
    protected static ?string $navigationGroup = 'System';

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill($this->getSettingsData());
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Tabs::make('Settings')
                    ->tabs([
                        Forms\Components\Tabs\Tab::make('General')
                            ->schema([
                                Forms\Components\Section::make('Application Settings')
                                    ->schema([
                                        Forms\Components\TextInput::make('app.name')
                                            ->label('Application Name')
                                            ->required()->readOnly()
                                            ->maxLength(255),
                                        
                                        Forms\Components\TextInput::make('app.hospital_name')
                                            ->label('Hospital Name')
                                            ->required()
                                            ->maxLength(255),
                                        
                                        Forms\Components\Textarea::make('app.description')
                                            ->label('Application Description')
                                            ->maxLength(500)
                                            ->rows(3),
                                        
                                        Forms\Components\Select::make('app.timezone')
                                            ->label('Timezone')
                                            ->options([
                                                'Asia/Colombo' => 'Asia/Colombo',
                                                'UTC' => 'UTC',
                                                'Asia/Karachi' => 'Asia/Karachi',
                                                'Asia/Dhaka' => 'Asia/Dhaka',
                                                'Asia/Kolkata' => 'Asia/Kolkata',
                                            ])
                                            ->default('Asia/Colombo')
                                            ->required(),
                                    ]),
                            ]),
                        
                        Forms\Components\Tabs\Tab::make('Nutrition')
                            ->schema([
                                // Forms\Components\Section::make('Nutrition Settings')
                                //     ->schema([
                                //         Forms\Components\Toggle::make('nutrition.auto_calculate')
                                //             ->label('Auto Calculate Nutrition Values')
                                //             ->default(true),
                                        
                                //         Forms\Components\TextInput::make('nutrition.default_serving_size')
                                //             ->label('Default Serving Size (grams)')
                                //             ->numeric()
                                //             ->default(100)
                                //             ->suffix('g'),
                                        
                                //         Forms\Components\Select::make('nutrition.calculation_method')
                                //             ->label('Calculation Method')
                                //             ->options([
                                //                 'standard' => 'Standard',
                                //                 'precise' => 'Precise',
                                //                 'estimated' => 'Estimated',
                                //             ])
                                //             ->default('standard'),
                                //     ]),
                            ]),
                        
                        Forms\Components\Tabs\Tab::make('Hospital Units')
                            ->schema([
                                // Forms\Components\Section::make('Unit Management')
                                //     ->schema([
                                //         Forms\Components\Toggle::make('units.auto_assign_diets')
                                //             ->label('Auto Assign Diets to New Units')
                                //             ->default(false),
                                        
                                //         Forms\Components\TextInput::make('units.default_capacity')
                                //             ->label('Default Unit Capacity')
                                //             ->numeric()
                                //             ->default(20),
                                        
                                //         Forms\Components\Select::make('units.default_room_type')
                                //             ->label('Default Room Type')
                                //             ->options([
                                //                 'general' => 'General Ward',
                                //                 'private' => 'Private Room',
                                //                 'icu' => 'ICU',
                                //                 'emergency' => 'Emergency',
                                //             ])
                                //             ->default('general'),
                                //     ]),
                            ]),
                        
                        Forms\Components\Tabs\Tab::make('Reports')
                            ->schema([
                                Forms\Components\Section::make('Report Settings')
                                    ->schema([
                                        Forms\Components\Select::make('reports.export_page_size')
                                            ->label('Report Page Export Size')
                                            ->helperText('Used for PDF/print exports')
                                            ->options([
                                                'A0' => 'A0 (841 × 1189 mm)',
                                                'A1' => 'A1 (594 × 841 mm)',
                                                'A2' => 'A2 (420 × 594 mm)',
                                                'A3' => 'A3 (297 × 420 mm)',
                                                'A4' => 'A4 (210 × 297 mm)',
                                                'A5' => 'A5 (148 × 210 mm)',
                                                'A6' => 'A6 (105 × 148 mm)',
                                                'B0' => 'B0 (1000 × 1414 mm)',
                                                'B1' => 'B1 (707 × 1000 mm)',
                                                'B2' => 'B2 (500 × 707 mm)',
                                                'B3' => 'B3 (353 × 500 mm)',
                                                'B4' => 'B4 (250 × 353 mm)',
                                                'B5' => 'B5 (176 × 250 mm)',
                                                'B6' => 'B6 (125 × 176 mm)',
                                                'Letter' => 'US Letter (216 × 279 mm)',
                                                'Legal' => 'US Legal (216 × 356 mm)',
                                            ])
                                            ->default('A4')
                                            ->searchable()
                                            ->native(false),
                                    ]),
                            ]),
                        
                        Forms\Components\Tabs\Tab::make('Security')
                            ->schema([
                                Forms\Components\Section::make('Security Settings')
                                    ->schema([
                                        Forms\Components\Toggle::make('security.enable_activity_log')
                                            ->label('Enable Activity Logging')
                                            ->disabled()
                                            ->default(true),
                                        
                                        Forms\Components\TextInput::make('security.session_timeout')
                                            ->label('Session Timeout (minutes)')
                                            ->numeric()
                                            ->default(120)
                                            ->suffix('min'),
                                        
                                        Forms\Components\Toggle::make('security.require_password_change')
                                            ->label('Require Password Change on First Login')
                                            ->default(true),
                                        
                                        Forms\Components\TextInput::make('security.password_min_length')
                                            ->label('Minimum Password Length')
                                            ->numeric()
                                            ->default(8)
                                            ->rule('min:6|max:20'),
                                    ]),
                            ]),
                    ])
                    ->columnSpanFull(),
            ])
            ->statePath('data');
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('save')
                ->label('Save Settings')
                ->icon('heroicon-m-check')
                ->color('primary')
                ->action('save'),
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('save')
                ->label('Save Settings')
                ->icon('heroicon-m-check')
                ->color('primary')
                ->action('save'),
        ];
    }

    public function save(): void
    {
        try {
            $data = $this->form->getState();
            
            foreach ($data as $key => $value) {
                $this->saveSetting($key, $value);
            }

            Notification::make()
                ->title('Settings saved successfully')
                ->success()
                ->send();

        } catch (Halt $exception) {
            return;
        }
    }

    protected function saveSetting(string $key, $value, string $category = null): void
    {
        if ($category === null) {
            $category = explode('.', $key)[0] ?? 'general';
        }

        $type = $this->getValueType($value);
        
        if (is_array($value) || is_object($value)) {
            $value = json_encode($value);
        } elseif (is_bool($value)) {
            $value = $value ? '1' : '0';
        }

        Setting::set($key, $value, $type, $category);
    }

    protected function getValueType($value): string
    {
        if (is_bool($value)) {
            return 'boolean';
        } elseif (is_int($value)) {
            return 'integer';
        } elseif (is_float($value)) {
            return 'float';
        } elseif (is_array($value) || is_object($value)) {
            return 'json';
        }
        
        return 'string';
    }

    protected function getSettingsData(): array
    {
        $settings = Setting::all()->pluck('value', 'key')->toArray();
        
        // Convert boolean strings back to actual booleans for the form
        foreach ($settings as $key => $value) {
            $setting = Setting::where('key', $key)->first();
            if ($setting && $setting->type === 'boolean') {
                $settings[$key] = filter_var($value, FILTER_VALIDATE_BOOLEAN);
            } elseif ($setting && $setting->type === 'integer') {
                $settings[$key] = (int) $value;
            } elseif ($setting && $setting->type === 'float') {
                $settings[$key] = (float) $value;
            } elseif ($setting && $setting->type === 'json') {
                $settings[$key] = json_decode($value, true);
            }
        }

        // Set default values if settings don't exist
        $defaults = [
            'app.APP_NAME' => env('APP_NAME', 'NutriCount'),
            'app.hospital_name' => config('app.hospital_name', 'Hospital'),
            'app.description' => 'DietNutrition management system for hospitals',
            'app.timezone' => 'Asia/Colombo',
            'reports.export_page_size' => 'A4',
            // 'nutrition.auto_calculate' => true,
            // 'nutrition.default_serving_size' => 100,
            // 'nutrition.calculation_method' => 'standard',
            // 'units.auto_assign_diets' => false,
            // 'units.default_capacity' => 20,
            // 'units.default_room_type' => 'general',
            // 'reports.enable_export' => true,
            // 'reports.default_format' => 'excel',
            // 'reports.records_per_page' => 25,
            // 'security.enable_activity_log' => true,
            'security.session_timeout' => 120,
            'security.require_password_change' => true,
            'security.password_min_length' => 8,
        ];

        return array_merge($defaults, $settings);
    }

    public static function canAccess(): bool
    {
        $user = Auth::user();
        return $user ? userHasPermission($user, 'edit.settings') : false;
    }
}
