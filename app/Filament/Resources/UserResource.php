<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use App\Helpers\PermissionHelper;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-s-users';
    protected static ?string $navigationGroup = 'User Management';

    public static function canCreate(): bool
    {
        $user = Auth::user();
        return $user ? userHasPermission($user, 'create.user') : false;
    }
    
    public static function canEdit(Model $record): bool
    {
        $user = Auth::user();
        return $user ? userHasPermission($user, 'edit.user') : false;
    }
    
    public static function canDelete(Model $record): bool
    {
        $user = Auth::user();
        return $user ? userHasPermission($user, 'delete.user') : false;
    }
    
    public static function canDeleteAny(): bool
    {
        $user = Auth::user();
        return $user ? userHasPermission($user, 'delete.user') : false;
    }
    
    public static function canView(Model $record): bool
    {
        $user = Auth::user();
        return $user ? userHasPermission($user, 'view.user') : false;
    }
    
    public static function canViewAny(): bool
    {
        $user = Auth::user();
        return $user ? userHasPermission($user, 'list.user') : false;
    }


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')->required()->columnspanfull(),
                Forms\Components\TextInput::make('email')->email()
                ->required()->columnspanfull(),
                Forms\Components\TextInput::make('password')
                    ->password()
                    ->dehydrateStateUsing(fn ($state) => $state ? bcrypt($state) : null)
                    ->required(fn ($context) => $context === 'create')
                    ->visible(fn ($context) => $context === 'create'),
                Forms\Components\Select::make('role')
                    ->options(function () {
                        $user = Auth::user();
                        $options = [
                            'level1' => config('app.level_names.level1'),
                            'level2' => config('app.level_names.level2'),
                            'level3' => config('app.level_names.level3'),
                            'user' => 'user',
                            'guest' => 'guest',
                            'api_only' => 'Api only',
                        ];
                        
                        // Only show Admin option if current user is Admin
                        if ($user && $user->role === 'admin') {
                            $options = ['admin' => 'Admin'] + $options;
                        }
                        
                        return $options;
                    })
                    ->default('Guest')
                    ->required(),
                Forms\Components\Select::make('default_lang')
                    ->label('Default Language')
                    ->options([
                        'Sin' => 'Sinhala',
                        'Eng' => 'English',
                        'Tam' => 'Tamil',
                    ])
                    ->default('Eng'),
                Forms\Components\Select::make('units_assigned')
                    ->label('Units Assigned')
                    ->options(\App\Models\HospitalUnit::where('active', true)->pluck('name', 'id'))
                    ->multiple()
                    ->searchable(),
                Forms\Components\Toggle::make('active')->label('Active'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->searchable(),
                Tables\Columns\TextColumn::make('email')->searchable(),
                Tables\Columns\TextColumn::make('role')->searchable(),
                Tables\Columns\TextColumn::make('default_lang')->label('Language'),
                Tables\Columns\TextColumn::make('units_assigned')
                    ->label('Units Assigned')
                    ->formatStateUsing(function ($state) {
                        // Handle null, empty, or non-array values
                        if (!$state || !is_array($state) || count($state) === 0) {
                            return 'No units assigned';
                        }
                        
                        try {
                            $units = \App\Models\HospitalUnit::whereIn('id', $state)->pluck('name')->toArray();
                            return implode(', ', $units);
                        } catch (\Exception $e) {
                            return 'Error loading units';
                        }
                    }),
                Tables\Columns\ToggleColumn::make('active'),
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
