<?php

namespace App\Filament\Dashboard\Resources;

use App\Filament\Dashboard\Resources\NutritionOrderResource\Pages;
use App\Filament\Dashboard\Resources\NutritionOrderResource\RelationManagers;
use App\Models\NutritionOrder;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class NutritionOrderResource extends Resource
{
    protected static ?string $model = NutritionOrder::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
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
            'index' => Pages\ListNutritionOrders::route('/'),
            'create' => Pages\CreateNutritionOrder::route('/create'),
            'edit' => Pages\EditNutritionOrder::route('/{record}/edit'),
        ];
    }
}
