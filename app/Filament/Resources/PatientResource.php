<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PatientResource\Pages;
use App\Filament\Resources\PatientResource\RelationManagers;
use App\Models\Patient;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;



class PatientResource extends Resource
{
    protected static ?string $model = Patient::class;

    // Placeholder actions for BHT, PHN, NIC
    public static function bhtAction($state, callable $set, callable $get): null {
    if ($state === '1/25' || $state === '1/24' && env('APP_DEBUG') === true) {
            $set('full_name', 'Demo Patient');
            $set('gender', 'male');
            $set('date_of_birth', '1990-01-01');
            $set('age_years', 35);
            $set('age_months', 6);
            $set('age_days', 10);
            $set('civil_status', 'Single');
            $set('ethnicity', 'Sinhalese');
            $set('religion', 'Buddhist');
            $set('blood_group', 'O+');
            $set('occupation', 'Engineer');
            $set('contact_home', '0112345678');
            $set('contact_mobile', '0771234567');
            $set('address', json_encode([
                [
                    'line' => ['123, Demo Street'],
                    'city' => 'Colombo',
                    'country' => 'Sri Lanka',
                ]
            ]));
            $set('address_line1', '123, Demo Street');
            $set('address_city', 'Colombo');
            $set('address_country', 'Sri Lanka');
            $set('title', 'Mr.');
            $set('name', 'Mr. Demo Patient');
        }
        return null;
    
    }

    public static function phnAction($state, callable $set, callable $get) {
        if ($state === '0004-000001-0' || $state === '0001' && env('APP_DEBUG') === true) {
            $set('full_name', 'Demo Patient');
            $set('gender', 'male');
            $set('date_of_birth', '1990-01-01');
            $set('age_years', 35);
            $set('age_months', 6);
            $set('age_days', 10);
            $set('civil_status', 'Single');
            $set('ethnicity', 'Sinhalese');
            $set('religion', 'Buddhist');
            $set('blood_group', 'O+');
            $set('occupation', 'Engineer');
            $set('contact_home', '0112345678');
            $set('contact_mobile', '0771234567');
            $set('address', json_encode([
                [
                    'line' => ['123, Demo Street'],
                    'city' => 'Colombo',
                    'country' => 'Sri Lanka',
                ]
            ]));
            $set('address_line1', '123, Demo Street');
            $set('address_city', 'Colombo');
            $set('address_country', 'Sri Lanka');
            $set('title', 'Mr.');
            $set('name', 'Mr. Demo Patient');
        }
        return null;
    }

    public static function nicAction($state, callable $set, callable $get) {
        if ($state === '90V' || $state === '901111111V' || $state === '90v' && env('APP_DEBUG') === true) {
            $set('full_name', 'Demo Patient');
            $set('gender', 'male');
            $set('date_of_birth', '1990-01-01');
            $set('age_years', 35);
            $set('age_months', 6);
            $set('age_days', 10);
            $set('civil_status', 'Single');
            $set('ethnicity', 'Sinhalese');
            $set('religion', 'Buddhist');
            $set('blood_group', 'O+');
            $set('occupation', 'Engineer');
            $set('contact_home', '0112345678');
            $set('contact_mobile', '0771234567');
            $set('address', json_encode([
                [
                    'line' => ['123, Demo Street'],
                    'city' => 'Colombo',
                    'country' => 'Sri Lanka',
                ]
            ]));
            $set('address_line1', '123, Demo Street');
            $set('address_city', 'Colombo');
            $set('address_country', 'Sri Lanka');
            $set('title', 'Mr.');
            $set('name', 'Mr. Demo Patient');
        }
        return null;
    }


    public static function mutateFormDataBeforeCreate(array $data): array
    {
        if (empty($data['bht']) && empty($data['phn']) && empty($data['nic'])) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'bht' => 'At least one of BHT, PHN, or NIC must be provided.',
            ]);
        }
        return $data;
    }

    public static function mutateFormDataBeforeUpdate(array $data): array
    {
        if (empty($data['bht']) && empty($data['phn']) && empty($data['nic'])) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'bht' => 'At least one of BHT, PHN, or NIC must be provided.',
            ]);
        }
        return $data;
    }

    protected static ?string $navigationIcon = 'healthicons-f-hospitalized';
    protected static ?string $navigationGroup = 'NHSL';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('unit_id')
                    ->label('Hospital Unit')
                    ->options(\App\Models\HospitalUnit::query()->pluck('name', 'id')->toArray())
                    ->searchable()
                    ->required(),
                Forms\Components\Tabs::make()->columnSpanFull()
                    ->tabs([
                        Forms\Components\Tabs\Tab::make('Demographics')->columns(1)
                            ->schema([
                                Forms\Components\Section::make('Identifiers')
                                    ->schema([
                                        Forms\Components\TextInput::make('bht')
                                            ->label('BHT#')
                                            ->nullable()
                                            ->suffixAction(
                                                Forms\Components\Actions\Action::make('bht_action')
                                                    ->icon('heroicon-o-magnifying-glass')
                                                    ->tooltip('Future: Search or validate BHT')
                                                    ->action(fn ($state, $set, $get) => static::bhtAction($state, $set, $get))
                                            ),
                                        Forms\Components\TextInput::make('phn')
                                            ->label('PHN#')
                                            ->nullable()
                                            ->suffixAction(
                                                Forms\Components\Actions\Action::make('phn_action')
                                                    ->icon('heroicon-o-magnifying-glass')
                                                    ->tooltip('Future: Search or validate PHN')
                                                    ->action(fn ($state, $set, $get) => static::phnAction($state, $set, $get))
                                            ),
                                        Forms\Components\TextInput::make('nic')
                                            ->label('NIC#')
                                            ->nullable()
                                            ->suffixAction(
                                                Forms\Components\Actions\Action::make('nic_action')
                                                    ->icon('heroicon-o-magnifying-glass')
                                                    ->tooltip('Future: Search or validate NIC')
                                                    ->action(fn ($state, $set, $get) => static::nicAction($state, $set, $get))
                                            ),
                                    ])->columns(3),
                                Forms\Components\Section::make('Personal Details')->columns(5)
                                    ->schema([

                                        Forms\Components\Select::make('gender')
                                            ->label('Gender')->columnSpanFull()
                                            ->options([
                                                'male' => 'Male',
                                                'female' => 'Female',
                                                'other' => 'Other',
                                                'unknown' => 'Unknown',
                                            ])
                                            ->required()
                                            ->reactive()
                                            ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                                // Only auto-set if the user hasn't changed title manually
                                                $currentTitle = $get('title');
                                                if ($state === 'male' && in_array($currentTitle, ['Mr.', 'Mrs.', 'Ms.', 'Miss', ''])) {
                                                    $set('title', 'Mr.');
                                                } elseif ($state === 'female' && in_array($currentTitle, ['Mr.', 'Mrs.', 'Ms.', 'Miss', ''])) {
                                                    $set('title', 'Mrs.');
                                                } elseif ($state === 'other' && in_array($currentTitle, ['Mr.', 'Mrs.', 'Ms.', 'Miss', ''])) {
                                                    $set('title', 'Mx.');
                                                } elseif ($state === 'unknown' && in_array($currentTitle, ['Mr.', 'Mrs.', 'Ms.', 'Miss', ''])) {
                                                    $set('title', 'Other');
                                                }
                                            }),
                                        
                                        Forms\Components\Fieldset::make('Age Details')
                                            ->schema([
                                                Forms\Components\DatePicker::make('date_of_birth')
                                            ->label('Date of Birth')
                                            ->reactive()
                                            ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                                if ($state) {
                                                    $dob = \Carbon\Carbon::parse($state);
                                                    $now = \Carbon\Carbon::now();
                                                    $set('age_years', $now->diffInYears($dob));
                                                    $set('age_months', $now->diffInMonths($dob) % 12);
                                                    $set('age_days', $now->diffInDays($dob->copy()->addYears($now->diffInYears($dob))->addMonths($now->diffInMonths($dob) % 12)));
                                                }
                                            }),
                                            Forms\Components\TextInput::make('age_separator')
                                                ->label('or')->default('|')
                                                ->disabled()
                                                ->extraAttributes(['style' => 'text-align:center; background:transparent; border:none; color:#888; font-weight:bold;'])
                                                ->columnSpan(1),
                                                Forms\Components\TextInput::make('age_years')
                                                    ->label('Years')
                                                    ->numeric()
                                                    ->minValue(0)
                                                    ->maxValue(150)
                                                    ->reactive()
                                                    ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                                        $years = (int) $state;
                                                        $months = (int) $get('age_months');
                                                        $days = (int) $get('age_days');
                                                        if ($years || $months || $days) {
                                                            $dob = \Carbon\Carbon::now()->subYears($years)->subMonths($months)->subDays($days);
                                                            $set('date_of_birth', $dob->toDateString());
                                                        }
                                                    }),
                                                Forms\Components\TextInput::make('age_months')
                                                    ->label('Months')
                                                    ->numeric()
                                                    ->minValue(0)
                                                    ->maxValue(11)
                                                    ->reactive()
                                                    ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                                        $years = (int) $get('age_years');
                                                        $months = (int) $state;
                                                        $days = (int) $get('age_days');
                                                        if ($years || $months || $days) {
                                                            $dob = \Carbon\Carbon::now()->subYears($years)->subMonths($months)->subDays($days);
                                                            $set('date_of_birth', $dob->toDateString());
                                                        }
                                                    }),
                                                Forms\Components\TextInput::make('age_days')
                                                    ->label('Days')
                                                    ->numeric()
                                                    ->minValue(0)
                                                    ->maxValue(30)
                                                    ->reactive()
                                                    ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                                        $years = (int) $get('age_years');
                                                        $months = (int) $get('age_months');
                                                        $days = (int) $state;
                                                        if ($years || $months || $days) {
                                                            $dob = \Carbon\Carbon::now()->subYears($years)->subMonths($months)->subDays($days);
                                                            $set('date_of_birth', $dob->toDateString());
                                                        }
                                                    }),
                                            ])->columns(5),


                                        Forms\Components\Select::make('title')
                                            ->label('Title')
                                            ->options([
                                                'Mr.' => 'Mr.',
                                                'Mrs.' => 'Mrs.',
                                                'Ms.' => 'Ms.',
                                                'Miss' => 'Miss',
                                                'Dr.' => 'Dr.',
                                                'Prof.' => 'Prof.',
                                                'Rev.' => 'Rev.',
                                                'Sister' => 'Sister',
                                                'Brother' => 'Brother',
                                                'Master' => 'Master',
                                                'Baby' => 'Baby',
                                                'Mx.' => 'Mx.',
                                                'Other' => 'Other',
                                            ])
                                            ->default('Mr.')
                                            ->required()
                                            ->reactive()
                                            ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                                $set('name', trim(($state ? $state . ' ' : '') . ($get('full_name') ?? '')));
                                            }),
                                        Forms\Components\TextInput::make('full_name')
                                            ->label('Full Name')
                                            ->required()
                                            ->reactive()
                                            ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                                $set('name', trim(($get('title') ? $get('title') . ' ' : '') . ($state ?? '')));
                                            })->columnSpan(4),
                                        Forms\Components\TextInput::make('name')
                                            ->label('Name')
                                            ->required()
                                            ->columnSpanFull()
                                            ->readOnly()
                                            ->dehydrated()
                                            ->helperText('Auto-filled from Title and Full Name'),
                                        
                                    ]),
                                
                                
                            ]),
                            Forms\Components\Tabs\Tab::make('Contact')
                                    ->schema([
                                        Forms\Components\TextInput::make('contact_home')->label('Contact Number Home')->nullable(),
                                        Forms\Components\TextInput::make('contact_mobile')->label('Contact Number Mobile')->nullable(),
                                    ]),
                                    Forms\Components\Tabs\Tab::make('Address')
                                    ->schema([
                                        Forms\Components\TextInput::make('address_line1')
                                            ->label('Address Line 1')
                                            ->nullable()
                                            ->afterStateHydrated(function ($component, $state, $record) {
                                                if ($record && $record->address) {
                                                    $addressArr = json_decode($record->address, true);
                                                    if (is_array($addressArr) && isset($addressArr[0]['line'][0])) {
                                                        $component->state($addressArr[0]['line'][0]);
                                                    }
                                                }
                                            })
                                            ->dehydrateStateUsing(function ($state, $component) {
                                                return null; // handled in address field
                                            }),
                                        Forms\Components\TextInput::make('address_line2')
                                            ->label('Address Line 2')
                                            ->nullable()
                                            ->afterStateHydrated(function ($component, $state, $record) {
                                                if ($record && $record->address) {
                                                    $addressArr = json_decode($record->address, true);
                                                    if (is_array($addressArr) && isset($addressArr[0]['line'][1])) {
                                                        $component->state($addressArr[0]['line'][1]);
                                                    }
                                                }
                                            })
                                            ->dehydrateStateUsing(function ($state, $component) {
                                                return null;
                                            }),
                                        Forms\Components\TextInput::make('address_city')
                                            ->label('City')
                                            ->required()
                                            ->afterStateHydrated(function ($component, $state, $record) {
                                                if ($record && $record->address) {
                                                    $addressArr = json_decode($record->address, true);
                                                    if (is_array($addressArr) && isset($addressArr[0]['city'])) {
                                                        $component->state($addressArr[0]['city']);
                                                    }
                                                }
                                            })
                                            ->dehydrateStateUsing(function ($state, $component) {
                                                return null;
                                            }),
                                        Forms\Components\TextInput::make('address_district')
                                            ->label('District')
                                            ->nullable()
                                            ->afterStateHydrated(function ($component, $state, $record) {
                                                if ($record && $record->address) {
                                                    $addressArr = json_decode($record->address, true);
                                                    if (is_array($addressArr) && isset($addressArr[0]['district'])) {
                                                        $component->state($addressArr[0]['district']);
                                                    }
                                                }
                                            })
                                            ->dehydrateStateUsing(function ($state, $component) {
                                                return null;
                                            }),
                                        Forms\Components\TextInput::make('address_state')
                                            ->label('State')
                                            ->nullable()
                                            ->afterStateHydrated(function ($component, $state, $record) {
                                                if ($record && $record->address) {
                                                    $addressArr = json_decode($record->address, true);
                                                    if (is_array($addressArr) && isset($addressArr[0]['state'])) {
                                                        $component->state($addressArr[0]['state']);
                                                    }
                                                }
                                            })
                                            ->dehydrateStateUsing(function ($state, $component) {
                                                return null;
                                            }),
                                        Forms\Components\TextInput::make('address_postalCode')
                                            ->label('Postal Code')
                                            ->nullable()
                                            ->afterStateHydrated(function ($component, $state, $record) {
                                                if ($record && $record->address) {
                                                    $addressArr = json_decode($record->address, true);
                                                    if (is_array($addressArr) && isset($addressArr[0]['postalCode'])) {
                                                        $component->state($addressArr[0]['postalCode']);
                                                    }
                                                }
                                            })
                                            ->dehydrateStateUsing(function ($state, $component) {
                                                return null;
                                            }),
                                        Forms\Components\TextInput::make('address_country')
                                            ->label('Country')->default('Sri Lanka')
                                            ->nullable()
                                            ->afterStateHydrated(function ($component, $state, $record) {
                                                if ($record && $record->address) {
                                                    $addressArr = json_decode($record->address, true);
                                                    if (is_array($addressArr) && isset($addressArr[0]['country'])) {
                                                        $component->state($addressArr[0]['country']);
                                                    }
                                                }
                                            })
                                            ->dehydrateStateUsing(function ($state, $component) {
                                                return null;
                                            }),
                                        // Hidden field to store the FHIR address JSON
                                        Forms\Components\Hidden::make('address')
                                            ->dehydrateStateUsing(function ($state, $component) {
                                                $form = $component->getContainer()->getParentComponent();
                                                $data = [
                                                    'line' => array_values(array_filter([
                                                        $form->getState()['address_line1'] ?? null,
                                                        $form->getState()['address_line2'] ?? null,
                                                    ])),
                                                    'city' => $form->getState()['address_city'] ?? null,
                                                    'district' => $form->getState()['address_district'] ?? null,
                                                    'state' => $form->getState()['address_state'] ?? null,
                                                    'postalCode' => $form->getState()['address_postalCode'] ?? null,
                                                    'country' => $form->getState()['address_country'] ?? null,
                                                ];
                                                // Remove empty/null fields
                                                foreach ($data as $k => $v) {
                                                    if ($v === null || (is_array($v) && count($v) === 0)) {
                                                        unset($data[$k]);
                                                    }
                                                }
                                                return json_encode([$data]);
                                            }),
                                    ]),

                            Forms\Components\Tabs\Tab::make('Other Information')
                                    ->schema([
                                        Forms\Components\Select::make('civil_status')
                                            ->label('Civil Status')
                                            ->options([
                                                '' => '--Select--',
                                                'Single' => 'Single',
                                                'Married' => 'Married',
                                                'Divorced' => 'Divorced',
                                                'Widow' => 'Widow',
                                                'UnKnown' => 'UnKnown',
                                            ])
                                            ->nullable(),
                                        Forms\Components\Select::make('ethnicity')
                                            ->label('Ethnicity / Race')
                                            ->options([
                                                '' => '--Select--',
                                                'Sinhala' => 'Sinhala',
                                                'Tamil' => 'Tamil',
                                                'Muslim' => 'Muslim',
                                                'Burgher' => 'Burgher',
                                                'Malay' => 'Malay',
                                                'Moor' => 'Moor',
                                                'Kapiri' => 'Kapiri',
                                                'Foreigner' => 'Foreigner',
                                                'Other' => 'Other',
                                            ])
                                            ->nullable(),
                                        Forms\Components\Select::make('religion')
                                            ->label('Religion')
                                            ->options([
                                                '' => '--Select--',
                                                'Buddhism' => 'Buddhism',
                                                'Hindu' => 'Hindu',
                                                'Catholic' => 'Catholic',
                                                'Islam' => 'Islam',
                                                'Other' => 'Other',
                                            ])
                                            ->nullable(),
                                        Forms\Components\Select::make('blood_group')
                                            ->label('Blood Group')
                                            ->options([
                                                '' => '--Select--',
                                                'A-' => 'A-',
                                                'A+' => 'A+',
                                                'O-' => 'O-',
                                                'O+' => 'O+',
                                                'AB-' => 'AB-',
                                                'AB+' => 'AB+',
                                                'B+' => 'B+',
                                                'B-' => 'B-',
                                            ])
                                            ->nullable(),
                                        Forms\Components\TextInput::make('occupation')->label('Occupation')->nullable(),
                                    ]),
                        Forms\Components\Tabs\Tab::make('FHIR')
                            ->schema([
                                Forms\Components\Section::make('FHIR Details')
                                    ->schema([
                                        Forms\Components\TextInput::make('id')->label('FHIR Patient ID')->disabled()->dehydrated(false),
                                        Forms\Components\Toggle::make('active')->label('Active'),
                                        Forms\Components\TextInput::make('telecom')->label('Telecom')->nullable(),
                                        Forms\Components\TextInput::make('marital_status')->label('Marital Status')->nullable(),
                                        Forms\Components\TextInput::make('multiple_birth')->label('Multiple Birth')->nullable(),
                                        Forms\Components\TextInput::make('photo')->label('Photo')->nullable(),
                                        Forms\Components\TextInput::make('general_practitioner')->label('General Practitioner')->nullable(),
                                        Forms\Components\TextInput::make('managing_organization')->label('Managing Organization')->nullable(),
                                    ]),
                            ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('unit.name')->label('Hospital Unit')->searchable(),
                Tables\Columns\TextColumn::make('bht')->label('BHT')->searchable(),
                Tables\Columns\TextColumn::make('phn')->label('PHN')->searchable(),
                Tables\Columns\TextColumn::make('nic')->label('NIC'),
                Tables\Columns\TextColumn::make('name')->label(' Name')->searchable(),
                Tables\Columns\TextColumn::make('gender')->label('Gender'),
                Tables\Columns\TextColumn::make('date_of_birth')->label('Date of Birth'),
                Tables\Columns\TextColumn::make('civil_status')->label('Civil Status'),
                Tables\Columns\TextColumn::make('blood_group')->label('Blood Group'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPatients::route('/'),
            'create' => Pages\CreatePatient::route('/create'),
            'edit' => Pages\EditPatient::route('/{record}/edit'),
        ];
    }

    public static function canCreate(): bool
    {
        $user = Auth::user();
        return $user ? userHasPermission($user, 'create.Patient') : false;
    }
    
    public static function canEdit(Model $record): bool
    {
        $user = Auth::user();
        return $user ? userHasPermission($user, 'edit.Patient') : false;
    }
    
    public static function canDelete(Model $record): bool
    {
        $user = Auth::user();
        return $user ? userHasPermission($user, 'delete.Patient') : false;
    }
    
    public static function canDeleteAny(): bool
    {
        $user = Auth::user();
        return $user ? userHasPermission($user, 'delete.Patient') : false;
    }
    
    public static function canView(Model $record): bool
    {
        $user = Auth::user();
        return $user ? userHasPermission($user, 'view.Patient') : false;
    }
    
    public static function canViewAny(): bool
    {
        $user = Auth::user();
        return $user ? userHasPermission($user, 'list.Patient') : false;
    }
}
