<?php

namespace App\Filament\Simple\Resources;

use App\Filament\Simple\Resources\HospitalUnitDietAmountResource\Pages;
use App\Filament\Simple\Resources\HospitalUnitDietAmountResource\RelationManagers;
use App\Models\HospitalUnitDietAmount;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class HospitalUnitDietAmountResource extends Resource

{

    protected static ?string $model = HospitalUnitDietAmount::class;
    protected static ?string $navigationIcon = 'healthicons-o-clinical-f';

    protected static ?string $navigationGroup = 'Diet Amounts';
    protected static ?int $navigationSort = 2;

    protected static ?string $navigationLabel = 'List of Hospital Unit Diet Amounts';



    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\DatePicker::make('date')
                    ->label('Date')
                    ->required()
                    ->disabled(),
                Forms\Components\Select::make('hospital_unit_id')
                    ->label('Hospital Unit')
                    ->relationship('hospitalUnit', 'name')
                    ->required()
                    ->disabled(),
                Forms\Components\Select::make('simple_diet_id')
                    ->label('Simple Diet')
                    ->relationship('simpleDiet', 'DietName_en')
                    ->required()
                    ->disabled(),
                Forms\Components\Select::make('patient_id')
                    ->label('Patient')
                    ->relationship('patient', 'full_name')
                    ->nullable()
                    ->default(null)
                    ->options([
                        null => 'Ward Cumulative',
                    ])
                    ->disabled(),
                Forms\Components\TextInput::make('amount')
                    ->label('Amount')
                    ->numeric()
                    ->nullable()
                    ->disabled(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('date')
                    ->label('Date')
                    ->date(),
                Tables\Columns\TextColumn::make('hospitalUnit.name')
                    ->label('Hospital Unit')
                    ->searchable(),
                Tables\Columns\TextColumn::make('simpleDiet.DietName_en')
                    ->label('Simple Diet')
                    ->searchable(),
                Tables\Columns\TextColumn::make('patient.full_name')
                    ->label('Patient')
                    ->searchable()
                    ->formatStateUsing(fn ($state, $record) => is_null($record->patient_id) ? 'Ward Cumalative' : ($state ?: '')),
                Tables\Columns\TextColumn::make('amount')
                    ->label('Amount'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->modalHeading('Diet Amount Details')
                    ->modalWidth('2xl')
                    ->infolist([
                        \Filament\Infolists\Components\Section::make('Diet Amount Information')
                            ->schema([
                                \Filament\Infolists\Components\TextEntry::make('date')
                                    ->label('Date')
                                    ->date(),
                                \Filament\Infolists\Components\TextEntry::make('hospitalUnit.name')
                                    ->label('Hospital Unit'),
                                \Filament\Infolists\Components\TextEntry::make('simpleDiet.DietName_en')
                                    ->label('Simple Diet'),
                                \Filament\Infolists\Components\TextEntry::make('patient.full_name')
                                    ->label('Patient')
                                    ->formatStateUsing(fn ($state, $record) => 
                                        is_null($record->patient_id) ? 'Ward Cumulative' : ($state ?: 'No Patient')
                                    ),
                                \Filament\Infolists\Components\TextEntry::make('amount')
                                    ->label('Amount')
                                    ->formatStateUsing(fn ($state) => $state ?? 'Not specified'),
                            ])
                            ->columns(2),
                    ]),
                // No edit or delete actions
            ])
            ->bulkActions([
                // No bulk delete or edit actions
            ])
            ->paginationPageOptions([50,100, 250, 'all']);
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
            'index' => Pages\ListHospitalUnitDietAmounts::route('/'),
        ];
    }

    public static function canCreate(): bool
    {
        $user = Auth::user();
        return $user ? userHasPermission($user, 'create.HospitalUnitDietAmount_Simple-Panel') : false;
    }
    
    public static function canEdit(Model $record): bool
    {
        $user = Auth::user();
        return $user ? userHasPermission($user, 'edit.HospitalUnitDietAmount_Simple-Panel') : false;
    }
    
    public static function canDelete(Model $record): bool
    {
        $user = Auth::user();
        return $user ? userHasPermission($user, 'delete.HospitalUnitDietAmount_Simple-Panel') : false;
    }
    
    public static function canDeleteAny(): bool
    {
        $user = Auth::user();
        return $user ? userHasPermission($user, 'delete.HospitalUnitDietAmount_Simple-Panel') : false;
    }
    
    public static function canView(Model $record): bool
    {
        $user = Auth::user();
        return $user ? userHasPermission($user, 'view.HospitalUnitDietAmount_Simple-Panel') : false;
    }
    
    public static function canViewAny(): bool
    {
        $user = Auth::user();
        return $user ? userHasPermission($user, 'list.HospitalUnitDietAmount_Simple-Panel') : false;
    }

    
}
