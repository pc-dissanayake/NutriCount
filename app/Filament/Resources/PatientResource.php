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

class PatientResource extends Resource
{
    protected static ?string $model = Patient::class;

    protected static ?string $navigationIcon = 'healthicons-f-hospitalized';
    protected static ?string $navigationGroup = 'NHSL';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Tabs::make()
                    ->tabs([
                        Forms\Components\Tabs\Tab::make('Demographics')
                            ->schema([
                                Forms\Components\TextInput::make('bht')->label('BHT')->required(),
                                Forms\Components\TextInput::make('phn')->label('PHN')->required(),
                                Forms\Components\TextInput::make('nic')->label('NIC')->nullable(),
                                Forms\Components\TextInput::make('title')->label('Title')->required(),
                                Forms\Components\TextInput::make('full_name')->label('Full Name')->required(),
                                Forms\Components\TextInput::make('name')->label('Name')->required(),
                                Forms\Components\Select::make('gender')->label('Gender')->options([
                                    'male' => 'Male',
                                    'female' => 'Female',
                                    'other' => 'Other',
                                    'unknown' => 'Unknown',
                                ])->required(),
                                Forms\Components\DatePicker::make('date_of_birth')->label('Date of Birth')->required(),
                                Forms\Components\TextInput::make('civil_status')->label('Civil Status')->nullable(),
                                Forms\Components\TextInput::make('ethnicity')->label('Ethnicity')->nullable(),
                                Forms\Components\TextInput::make('religion')->label('Religion')->nullable(),
                                Forms\Components\TextInput::make('blood_group')->label('Blood Group')->nullable(),
                                Forms\Components\TextInput::make('occupation')->label('Occupation')->nullable(),
                                Forms\Components\TextInput::make('contact_home')->label('Contact Number Home')->nullable(),
                                Forms\Components\TextInput::make('contact_mobile')->label('Contact Number Mobile')->nullable(),
                                Forms\Components\Textarea::make('address')->label('Address')->required(),
                            ]),
                        Forms\Components\Tabs\Tab::make('FHIR')
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
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('bht')->label('BHT')->searchable(),
                Tables\Columns\TextColumn::make('phn')->label('PHN')->searchable(),
                Tables\Columns\TextColumn::make('nic')->label('NIC'),
                Tables\Columns\TextColumn::make('full_name')->label('Full Name')->searchable(),
                Tables\Columns\TextColumn::make('gender')->label('Gender'),
                Tables\Columns\TextColumn::make('date_of_birth')->label('Date of Birth'),
                Tables\Columns\TextColumn::make('civil_status')->label('Civil Status'),
                Tables\Columns\TextColumn::make('ethnicity')->label('Ethnicity'),
                Tables\Columns\TextColumn::make('religion')->label('Religion'),
                Tables\Columns\TextColumn::make('blood_group')->label('Blood Group'),
                Tables\Columns\TextColumn::make('address')->label('Address')->searchable(),
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
}
