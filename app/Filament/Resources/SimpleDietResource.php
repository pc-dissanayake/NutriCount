<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SimpleDietResource\Pages;
use App\Models\SimpleDiet;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class SimpleDietResource extends Resource
{
    protected static ?string $model = SimpleDiet::class;
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';
    protected static ?string $navigationGroup = 'NHSL';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('DietName_en')->label('Diet Name (English)')->required(),
                Forms\Components\TextInput::make('DietName_si')->label('Diet Name (Sinhala)')->nullable(),
                Forms\Components\TextInput::make('DietName_tm')->label('Diet Name (Tamil)')->nullable(),
                Forms\Components\Toggle::make('active')->label('Active')->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('DietName_en')->label('Diet Name (English)')->searchable(),
                Tables\Columns\TextColumn::make('DietName_si')->label('Diet Name (Sinhala)')->searchable(),
                Tables\Columns\TextColumn::make('DietName_tm')->label('Diet Name (Tamil)')->searchable(),
                Tables\Columns\ToggleColumn::make('active')->label('Active'),
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
            'index' => Pages\ListSimpleDiets::route('/'),
            'create' => Pages\CreateSimpleDiet::route('/create'),
            'edit' => Pages\EditSimpleDiet::route('/{record}/edit'),
        ];
    }
}
