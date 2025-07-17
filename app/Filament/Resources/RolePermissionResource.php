<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RolePermissionResource\Pages;
use App\Filament\Resources\RolePermissionResource\RelationManagers;
use App\Models\RolePermission;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ToggleColumn;

class RolePermissionResource extends Resource
{
    protected static ?string $model = RolePermission::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('permission')->required()->columnSpanFull(),
                Section::make('Permissions')
                    ->description('Assign permissions to this role')
                    ->columns(5)
                    ->schema([
                        Toggle::make('admin')->label('Admin Access')->default((true)),
                        Toggle::make('level1')->label(env("LEVEL_1_NAME").' Access'),
                        Toggle::make('level2')->label(env("LEVEL_2_NAME").' Access'),
                        Toggle::make('user')->label('User Access'),
                        Toggle::make('guest')->label('Guest Access'),
                        Toggle::make('api_only')->label('API Only Access'),
                    ])
                    ->columnSpanFull(),
                
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('permission')->searchable(),
                ToggleColumn::make('admin'),
                ToggleColumn::make('Ward Admin'),
                ToggleColumn::make('level1')->label(env("LEVEL_1_NAME")),
                ToggleColumn::make('level2')->label(env("LEVEL_2_NAME")),
                ToggleColumn::make('user'),
                ToggleColumn::make('guest'),
                ToggleColumn::make('api_only'),
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
            'index' => Pages\ListRolePermissions::route('/'),
            'create' => Pages\CreateRolePermission::route('/create'),
            'edit' => Pages\EditRolePermission::route('/{record}/edit'),
        ];
    }
}
