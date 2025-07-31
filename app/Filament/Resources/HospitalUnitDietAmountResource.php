<?php

namespace App\Filament\Resources;

use App\Filament\Resources\HospitalUnitDietAmountResource\Pages;
use App\Models\HospitalUnitDietAmount;
use App\Models\HospitalUnit;
use App\Models\SimpleDiet;
use App\Models\Patient;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class HospitalUnitDietAmountResource extends Resource
{
    protected static ?string $model = HospitalUnitDietAmount::class;
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';
    protected static ?string $navigationGroup = 'Diet Management';
    protected static ?string $navigationLabel = 'Unit Diet Amounts';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('hospital_unit_id')
                ->label('Hospital Unit')
                ->relationship('hospitalUnit', 'unit_name')
                ->required(),
            Forms\Components\Select::make('simple_diet_id')
                ->label('Diet')
                ->relationship('simpleDiet', 'name')
                ->required(),
            Forms\Components\Select::make('patient_id')
                ->label('Patient')
                ->relationship('patient', 'full_name')
                ->searchable()
                ->required(),
            Forms\Components\DatePicker::make('date')
                ->required(),
            Forms\Components\TextInput::make('amount')
                ->numeric()
                ->required(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('hospitalUnit.unit_name')->label('Unit'),
            Tables\Columns\TextColumn::make('simpleDiet.name')->label('Diet'),
            Tables\Columns\TextColumn::make('patient.full_name')->label('Patient'),
            Tables\Columns\TextColumn::make('date'),
            Tables\Columns\TextColumn::make('amount'),
        ])
        ->actions([
            Tables\Actions\EditAction::make(),
        ])
        ->bulkActions([
            Tables\Actions\DeleteBulkAction::make(),
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListHospitalUnitDietAmounts::route('/'),
            'create' => Pages\CreateHospitalUnitDietAmount::route('/create'),
            'edit' => Pages\EditHospitalUnitDietAmount::route('/{record}/edit'),
        ];
    }
}
