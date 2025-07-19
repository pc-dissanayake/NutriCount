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

class HospitalUnitDietAmountResource extends Resource
{
    protected static ?string $model = HospitalUnitDietAmount::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\DatePicker::make('date')
                    ->label('Date')
                    ->required()
                    ->disabled(fn ($record) => filled($record)),
                Forms\Components\Select::make('hospital_unit_id')
                    ->label('Hospital Unit')
                    ->relationship('hospitalUnit', 'name')
                    ->required()
                    ->disabled(fn ($record) => filled($record)),
                Forms\Components\Select::make('simple_diet_id')
                    ->label('Simple Diet')
                    ->relationship('simpleDiet', 'DietName_en')
                    ->required(),
                Forms\Components\Select::make('patient_id')
                    ->label('Patient')
                    ->relationship('patient', 'full_name')
                    ->nullable(),
                Forms\Components\TextInput::make('amount')
                    ->label('Amount')
                    ->numeric()
                    ->nullable(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
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
            'index' => Pages\ListHospitalUnitDietAmounts::route('/'),
            'create' => Pages\CreateHospitalUnitDietAmount::route('/create'),
            'view' => Pages\ViewHospitalUnitDietAmount::route('/{record}'),
            'edit' => Pages\EditHospitalUnitDietAmount::route('/{record}/edit'),
            'calendar' => Pages\CalendarHospitalUnitDietAmounts::route('/calendar'),
        ];
    }
}
